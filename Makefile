templates:
	php -q ./bin/compile-templates.php

secret:
	php -q ./bin/generate_secret.php

test:
	prove -v --exec 'php --php-ini ./tests/php.ini' ./tests/*.t

cover:
	rm -f ./tests/coverage.state
	rm -rf ./coverage
	-make test
	php -q ./tests/coverage.php

setup:
	ubuntu/setup-ubuntu.sh
	ubuntu/setup-flamework.sh
	ubuntu/setup-certified.sh
	sudo ubuntu/setup-certified-ca.sh
	sudo ubuntu/setup-certified-certs.sh
	ubuntu/setup-apache.sh
	bin/configure_secrets.php .
	ubuntu/setup-db.sh __PROJECTNAME__ __PROJECTNAME__
