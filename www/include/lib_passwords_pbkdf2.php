<?php

	# https://nakedsecurity.sophos.com/2013/11/20/serious-security-how-to-store-your-users-passwords-safely/
	# https://pages.nist.gov/800-63-3/sp800-63c.html
	# https://en.wikipedia.org/wiki/PBKDF2
	
	#################################################################

	function passwords_encrypt_password($password, $more=array()){

		$salt = $more['salt'];
		$iters = $more['iterations'];
			
		$hash = hash_pbkdf2("sha256", $password, $salt, $iters, 32);
		return array('ok' => 1, 'hash' => $hash, 'salt' => $salt, 'iterations' => $iters);
	}

	#################################################################

	function passwords_validate_password($password, $enc_password, $more=array()){

		$test = passwords_encrypt_password($password, $more);
		return $test == $enc_password;			
	}

	#################################################################

	function passwords_validate_password_for_user($password, &$user){

		$more = array('salt' => $user['salt'], $user['iterations']);
		return passwords_validate_password($password, $user['password'], $more);			
	}
	
	#################################################################

	# the end
