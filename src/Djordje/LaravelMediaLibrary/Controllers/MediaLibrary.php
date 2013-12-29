<?php namespace Djordje\LaravelMediaLibrary\Controllers;

use Illuminate\Support\Facades\Input;
use Djordje\LaravelMediaLibrary\Models\MediaFile;


/**
 * Application instance
 */
$app = app();

/**
 * Enable usage of this controller in Laravel v4.0 and v4.1
 */
if(version_compare($app::VERSION, '4.1', '>='))
{
	class BaseController extends \Illuminate\Routing\Controller {}
}
else
{
	class BaseController extends \Illuminate\Routing\Controllers\Controller {}
}

/**
 * Class MediaLibrary is base for controllers that should work with media files.
 * You can extend this class and have base for your controller.
 *
 * @package Djordje\LaravelMediaLibrary\Controllers
 */
abstract class MediaLibrary extends BaseController {

	/**
	 * Find resource by id
	 *
	 * @param int $id
	 * @return MediaFile|null
	 */
	protected function getById($id)
	{
		return MediaFile::find($id) ?: null;
	}

	/**
	 * Return all resources
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function index()
	{
		return MediaFile::all();
	}

	/**
	 * Store new resource
	 *
	 * @return \Illuminate\Database\Eloquent\Model|static
	 */
	public function store()
	{
		$input = Input::all();

		return MediaFile::create($input);
	}

	/**
	 * Updated existing resource
	 *
	 * @param int $id
	 * @return mixed|null
	 */
	public function update($id)
	{
		if ($mediaFile = $this->getById($id))
		{
			$input = Input::all();

			return $mediaFile->update($input);
		}

		return null;
	}

	/**
	 * Destroy existing resource
	 *
	 * @param int $id
	 * @return int
	 */
	public function destroy($id)
	{
		return MediaFile::destroy($id);
	}

}