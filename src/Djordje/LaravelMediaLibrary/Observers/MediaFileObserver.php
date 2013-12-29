<?php namespace Djordje\LaravelMediaLibrary\Observers;

use Djordje\LaravelMediaLibrary\Models\MediaFile;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFileObserver {

	public function creating(MediaFile $model)
	{
		if ( ! ($model->file instanceof UploadedFile))
		{
			throw new UploadException;
		}

		$model->moveUploadedFile();
		$model->unsetFile();
	}

	public function updating(MediaFile $model)
	{
		if ($model->file instanceof UploadedFile)
		{
			$model->removeFile();
			$model->moveUploadedFile();
		}

		$model->unsetFile();
	}

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