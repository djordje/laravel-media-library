<?php namespace Djordje\LaravelMediaLibrary\Observers;

use Djordje\LaravelMediaLibrary\Models\MediaFile;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFileObserver
{

	/**
	 * Move uploaded file and unset file property.
	 *
	 * @param MediaFile $model
	 * @throws \Symfony\Component\HttpFoundation\File\Exception\UploadException
	 */
	public function creating(MediaFile $model)
	{
		if ( ! ($model->file instanceof UploadedFile))
		{
			throw new UploadException;
		}

		$model->moveUploadedFile();
		$model->unsetFile();
	}

	/**
	 * If updating file remove old, upload new and unset file property.
	 *
	 * @param MediaFile $model
	 */
	public function updating(MediaFile $model)
	{
		if ($model->file instanceof UploadedFile)
		{
			$model->removeFile();
			$model->moveUploadedFile();
		}

		$model->unsetFile();
	}

	/**
	 * Remove file too.
	 *
	 * @param MediaFile $model
	 */
	public function deleting(MediaFile $model)
	{
		$model->removeFile();
	}

	public function created(MediaFile $model)
	{
		Event::fire('media_file.created', array($model));
	}

	public function updated(MediaFile $model)
	{
		Event::fire('media_file.updated', array($model));
	}

	public function deleted(MediaFile $model)
	{
		Event::fire('media_file.deleted', array($model));
	}

}