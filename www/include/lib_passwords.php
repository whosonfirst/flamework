<?php

	# https://nakedsecurity.sophos.com/2013/11/20/serious-security-how-to-store-your-users-passwords-safely/
	# https://pages.nist.gov/800-63-3/sp800-63c.html
	# https://en.wikipedia.org/wiki/PBKDF2
	
	#
	# if we're using bcrypt, ensure we have it installed
	#

	if ($GLOBALS['cfg']['passwords_use_bcrypt']){

		if (!CRYPT_BLOWFISH) die("CRYPT_BLOWFISH is required for using bcrypt");
		loadlib("bcrypt");
	}


	#
	# if we're not using bcrypt, *or* we allow hamc promotion, when we have a secret set
	#

	if (!$GLOBALS['cfg']['passwords_use_bcrypt'] || $GLOBALS['cfg']['passwords_allow_promotion']){

		if (!strlen($GLOBALS['cfg']['crypto_password_secret'])){

			die("You must set cfg.crypto_password_secret unless you use bcrypt (without auto-promotion)");
		}
	}

	#################################################################

	function passwords_encrypt_password($password, $more=array()){

		if ($GLOBALS['cfg']['passwords_use_bcrypt']){

			$h = new BCryptHasher();
			return $h->HashPassword($password);
		}

		if ($GLOBALS['cfg']['passwords_use_pbkdf2']){

			$salt = $more['salt'];
			$iters = $more['iterations'];
			
			$hash = hash_pbkdf2("sha256", $password, $salt, $iters, 32);
			return array('ok' => 1, 'hash' => $hash, 'salt' => $salt, 'iterations' => $iters);
		}
		
		return hash_hmac("sha256", $password, $GLOBALS['cfg']['crypto_password_secret']);
	}

	#################################################################

	function passwords_validate_password($password, $enc_password, $more=array()){

		if ($GLOBALS['cfg']['passwords_use_bcrypt']){

			$h = new BCryptHasher();
			return $h->CheckPassword($password, $enc_password);
		}

		if ($GLOBALS['cfg']['passwords_use_pbkdf2']){

			$test = passwords_encrypt_password($password, $more);
			return $test == $enc_password;			
		}
		
		$test = passwords_encrypt_password($password);
		return $test == $enc_password;
	}

	#################################################################

	# a helper function which performs password hash promotion when a hash
	# is not yet bcrypt and we're configured to allow it.

	function passwords_validate_password_for_user($password, &$user){

		#
		# is this is *not* a bcrypt hash, but we allow promotion,
		# then verify & promote it.
		#

		$is_bcrypt = substr($user['password'], 0, 4) == '$2a$';

		if ($GLOBALS['cfg']['passwords_use_bcrypt'] && $GLOBALS['cfg']['passwords_allow_promotion'] && !$is_bcrypt){

			$test = hash_hmac("sha256", $password, $GLOBALS['cfg']['crypto_password_secret']);

			$is_ok = $test == $user['password'];

			if ($is_ok){

				if (users_update_password($user, $password)){

					$user = users_get_by_id($user['id']);
				}
			}

			return $is_ok;
		}

		if ($GLOBALS['cfg']['passwords_use_pbkdf2']){

			$more = array('salt' => $user['salt'], $user['iterations']);
			return passwords_validate_password($password, $user['password'], $more);			
		}

		# simple case

		return passwords_validate_password($password, $user['password']);
	}

	#################################################################

	function passwords_generate_salt(){

		$salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
		return $salt;
	}
	
	#################################################################

	# the end
