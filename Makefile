test:
	@vendor/bin/phpunit -v --coverage-clover=coverage.clover

example-anvil-publish:
	cd ../anvil-example && php artisan anvil:publish
