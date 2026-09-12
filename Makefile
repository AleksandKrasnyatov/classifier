test:
	php -d error_reporting="E_ALL & ~E_DEPRECATED" ./vendor/bin/phpunit
