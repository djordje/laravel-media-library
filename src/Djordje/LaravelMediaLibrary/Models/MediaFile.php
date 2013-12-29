<?php namespace Djordje\LaravelMediaLibrary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\File as FileFacade;

class MediaFile extends Model {

	protected $fillable = array('file', 'path', 'filename', 'public_url', 'name', 'description', 'parent_id', 'parent_type');

	/**
	 * Uploaded file destination in public directory
	 *
	 * @var string
	 */
	protected $destination = 'assets/media';

	/**
	 * Get parent object if exists.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
	 */
	public function parent()
	{
		if ($this->parent_id && $this->parent_type)
		{
			return $this->morphTo();
		}

		return null;
	}

	/**
	 * Get File instance for current media file.
	 *
	 * @return File
	 */
	public function getFileInstance()
	{
		if ($this->path && $this->filename)
		{
			return new File($this->path.'/'.$this->filename);
		}
	}

	/**
	 * Get destination path.
	 * Takes $destination property and generate public path.
	 *
	 * @return string
	 */
	protected function getDestination()
	{
		return public_path($this->destination);
	}

	/**
	 * Generate unique file name and append passed extension.
	 *
	 * @param string $extension
	 * @return string
	 */
	protected function generateFilename($extension)
	{
		return date('YmdHis').mt_rand(10, 99).'.'.$extension;
	}

	/**
	 * Move uploaded file and setup path, filename and public_url properties.
	 */
	protected function moveUploadedFile()
	{
		if ( ! isset($this->file))
		{
			return null;
		}

		$name = $this->generateFilename($this->file->getClientOriginalExtension());

		if ($file = $this->file->move($this->getDestination(), $name))
		{
			$this->path = $file->getPath();
			$this->filename = $file->getFilename();
			$this->public_url = '/'.$this->destination.'/'.$this->filename;
		}
	}

	/**
	 * Remove file by path and filename fetched from database.
	 *
	 * @return mixed
	 */
	protected function removeFile()
	{
		if ($this->exists)
		{
			return FileFacade::delete($this->path.'/'.$this->filename);
		}
	}

	/**
	 * Unset temp file property.
	 */
	protected function unsetFile()
	{
		unset($this->file);
	}

	/**
	 * Register model events.
	 */
	protected static function boot() {
		parent::boot();

		// Move uploaded file and unset file property.
		static::creating(function($mediaFile)
		{
			if ( ! ($mediaFile->file instanceof UploadedFile))
			{
				throw new UploadException;
			}

			$mediaFile->moveUploadedFile();

			$mediaFile->unsetFile();
		});

		// If updating file remove old, upload new and unset file property.
		static::updating(function($mediaFile)
		{
			if ($mediaFile->file instanceof UploadedFile)
			{
				$mediaFile->removeFile();
				$mediaFile->moveUploadedFile();
			}

			$mediaFile->unsetFile();
		});

		// Remove file too.
		static::deleting(function($mediaFile)
		{
			$mediaFile->removeFile();
		});

		// Fire events and pass object instance to observers.
		static::created(function($mediaFile)
		{
			Event::fire('media_file.created', array($mediaFile));
		});

		static::updated(function($mediaFile)
		{
			Event::fire('media_file.updated', array($mediaFile));
		});

		static::deleted(function($mediaFile)
		{
			Event::fire('media_file.deleted', array($mediaFile));
		});
	}

}