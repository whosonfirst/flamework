<?php

	#################################################################

	function passwords_utils_generate_salt(){

		$salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
		return $salt;
	}

	#################################################################

	# the end
