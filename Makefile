templates:
	php -q ./bin/compile-templates.php

secret:
	php -q ./bin/generate_secret.php

setup:
	if test -z "$$DBNAME"; then echo "YOU FORGET TO SPECIFY DBNAME"; exit 1; fi
	if test -z "$$DBUSER"; then echo "YOU FORGET TO SPECIFY DBUSER"; exit 1; fi
	if test ! -f www/include/secrets.php; then cp www/include/secrets.php.example www/include/secrets.php; fi
	ubuntu/setup-ubuntu.sh
	ubuntu/setup-flamework.sh
	ubuntu/setup-certified.sh
	sudo ubuntu/setup-certified-ca.sh
	sudo ubuntu/setup-certified-certs.sh
	bin/configure_secrets.sh .
	ubuntu/setup-db.sh $(DBNAME) $(DBUSER)

defuse:
	curl -v -L -o www/include/defuse-crypto/defuse-crypto.phar https://github.com/defuse/php-encryption/releases/download/v2.1.0/defuse-crypto.phar
	curl -v -L -o www/include/defuse-crypto/defuse-crypto.phar.sig https://github.com/defuse/php-encryption/releases/download/v2.1.0/defuse-crypto.phar.sig
	# curl -o www/include/defuse-crypto/signingkey.asc https://raw.githubusercontent.com/defuse/php-encryption/master/dist/signingkey.asc
	# gpg --import www/include/defuse-crypto/signingkey.asc
	# gpg --verify www/include/defuse-crypto/defuse-crypto.phar.sig www/include/defuse-crypto/defuse-crypto.phar
