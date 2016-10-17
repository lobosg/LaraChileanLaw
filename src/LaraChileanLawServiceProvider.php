<?php

namespace Unforgivencl\LaraChileanLaw;

use Unforgivencl\LaraChileanLaw\LawsApi\ApiRequest;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class LaraChileanLawServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
    {
        $this->app->bind('law', function () {
            return new ApiRequest(new Client());
        });
    }
}
