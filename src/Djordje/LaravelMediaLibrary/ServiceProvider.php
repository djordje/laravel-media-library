<?php namespace Djordje\LaravelMediaLibrary;

use Djordje\LaravelMediaLibrary\Models\MediaFile;
use Djordje\LaravelMediaLibrary\Observers\MediaFileObserver;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('djordje/laravel-media-library');
        MediaFile::observe(new MediaFileObserver());
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}