<?php

	#################################################################

	function passwords_encrypt_password($password, $more=array()){
		
		return hash_hmac("sha256", $password, $GLOBALS['cfg']['crypto_password_secret']);
	}

	#################################################################

	function passwords_validate_password($password, $enc_password, $more=array()){
		
		$test = passwords_encrypt_password($password);
		return $test == $enc_password;
	}

	#################################################################

	function passwords_validate_password_for_user($password, &$user){

		return passwords_validate_password($password, $user['password']);
	}

	#################################################################

	# the end
