test:
	@vendor/bin/phpunit

example-anvil-publish:
	cd ../anvil-example && php artisan anvil:publish
