<?php

	# https://github.com/defuse/php-encryption

	require_once("defuse-crypto/defuse-crypto.phar");

	use Defuse\Crypto\Crypto;
	use Defuse\Crypto\Key;
	
	#################################################################
	
	function crypto_encrypt($data, $secret){

		$key = Key::loadFromAsciiSafeString($secret);
		return Crypto::encrypt($data, $key);
	}

	#################################################################
	
	function crypto_decrypt($ciphertext, $key){

		$key = Key::loadFromAsciiSafeString($secret);
		
		Crypto::decrypt($ciphertext, $secret);
	}

	#################################################################
	# the end