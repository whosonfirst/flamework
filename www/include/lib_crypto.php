<?php

	# https://paragonie.com/blog/2015/05/using-encryption-and-authentication-correctly
	# https://paragonie.com/blog/2015/05/if-you-re-typing-word-mcrypt-into-your-code-you-re-doing-it-wrong
	# https://paragonie.com/blog/2017/02/cryptographically-secure-php-development
	
	# https://paragonie.com/blog/2017/06/libsodium-quick-reference-quick-comparison-similar-functions-and-which-one-use
	# https://paragonie.com/book/pecl-libsodium/read/09-recipes.md#encrypted-cookies
	# https://github.com/defuse/php-encryption/blob/master/docs/Tutorial.md
	
	if (!defined('MCRYPT_RIJNDAEL_256')){
		 die("[lib_crypto] Flamework requires MCRYPT_RIJNDAEL_256");
	}

	if (!defined('MCRYPT_MODE_ECB')){
		die("[lib_crypto] Flamework requires MCRYPT_MODE_ECB");
	}

	#################################################################

	function crypto_encrypt($data, $key){

		if (!strlen($key)){
			die("[lib_crypto] Trying to encrypt with a blank key");
		}

		$key = hash('sha256', $key, true);

		$enc = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB);
		return base64_encode($enc);
	}

	#################################################################

	function crypto_decrypt($enc_b64, $key){

		if (strlen($key)) $key = hash('sha256', $key, true);

		$enc = base64_decode($enc_b64);
		$dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $enc, MCRYPT_MODE_ECB);

		return trim($dec);
	}

	#################################################################

