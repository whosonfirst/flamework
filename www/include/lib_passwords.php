<?php

	if (! strlen($GLOBALS['cfg']['crypto_password_secret'])){
		die("You must set cfg.crypto_password_secret");
	}

	#################################################################
	
	# loadlib("passwords_hmac");
	loadlib("passwords_bcrypt");

	# the end