<?php

/*
 * This file is part of the Brickrouge package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brickrouge;

use ICanBoogie\Uploaded;

class File extends Element
{
	const FILE_WITH_LIMIT = '#file-with-limit';
	const T_UPLOAD_URL = '#file-upload-url';
	const BUTTON_LABEL = '#file-button-label';

	static protected function add_assets(Document $document)
	{
		parent::add_assets($document);

		$document->js->add('file.js');
		$document->css->add('file.css');
	}

	public function __construct(array $attributes=array())
	{
		parent::__construct
		(
			'div', $attributes + array
			(
				Element::WIDGET_CONSTRUCTOR => 'File',

				self::BUTTON_LABEL => 'Choose a file',

				'class' => 'widget-file'
			)
		);
	}

	protected function infos()
	{
		$path = $this['value'];
		$details = $this->details($path);
		$preview = $this->preview($path);

		$rc = '';

		if ($preview)
		{
			$rc .= '<div class="preview">';
			$rc .= $preview;
			$rc .= '</div>';
		}

		if ($details)
		{
			$rc .= '<ul class="details">';

			foreach ($details as $detail)
			{
				$rc .= '<li>' . $detail . '</li>';
			}

			$rc .= '</ul>';
		}

		return $rc;
	}

	protected function details($path)
	{
		$basename = basename($path);

		$rc[] = '<span title="' . $basename . '">' . \ICanBoogie\shorten($basename, 40) . '</span>';
		$rc[] = Uploaded::getMIME($path);
		$rc[] = format_size(filesize($path));

		return $rc;
	}

	protected function preview($path)
	{
		return '<a class="download" href="' . $path . '">'
		. t('download', array(), array('scope' => 'fileupload.element'))
		. '</a>';
	}

	protected function render_inner_html()
	{
		$name = $this['name'];
		$path = $this['value'];

		$rc = $this->render_reminder($name, $path)

		. ' <div class="alert alert-error undissmisable"></div>'
		. ' <label class="btn trigger"><i class="icon-file"></i> '
		. t($this[self::BUTTON_LABEL], array(), array('scope' => 'button'))

		. new Element
		(
			'input', array
			(
				'type' => "file",
				'name' => $this[self::T_UPLOAD_URL] ? null : $name
			)
		)

		. '</label>';

		#
		# uploading element
		#

		$rc .= '<div class="uploading">';
		$rc .= '<span class="progress like-input"><span class="position"><span class="text">&nbsp;</span></span></span> ';
		$rc .= '<button type="button" class="btn btn-danger cancel">' . t('cancel', array(), array('scope' => 'button')) . '</button>';
		$rc .= '</div>';

		#
		# the FILE_WITH_LIMIT tag can be used to add a little text after the element
		# reminding the maximum file size allowed for the upload
		#

		$limit = $this[self::FILE_WITH_LIMIT];

		if ($limit)
		{
			if ($limit === true)
			{
				$limit = ini_get('upload_max_filesize') * 1024;
			}

			$limit = format_size($limit * 1024);

			$rc .= PHP_EOL . '<div class="file-size-limit help-block" style="margin-top: .5em">';
			$rc .= t('The maximum file size must be less than :size.', array(':size' => $limit));
			$rc .= '</div>';
		}

		#
		# infos
		#

		$infos = null;

		if ($path)
		{
			if (!is_file($path))
			{
				$infos = '<span class="warn">' . t('The file %file is missing !', array('%file' => basename($path))) . '</span>';
			}
			else
			{
				$infos = $this->infos();
			}

			if ($infos)
			{
				$this->add_class('has-info');
			}
		}

		return $rc . <<<EOT
<div class="infos">$infos</div>
EOT;
	}

	protected function render_reminder($name, $value)
	{
		return new Text
		(
			array
			(
				'value' => $value,
				'readonly' => true,
				'name' => $name,
				'class' => 'reminder'
			)
		);
	}

	protected function alter_dataset(array $dataset)
	{
		$limit = $this[self::FILE_WITH_LIMIT] ?: 2 * 1024;

		if ($limit === true)
		{
			$limit = ini_get('upload_max_filesize') * 1024;
		}

		return parent::alter_dataset($dataset) + array
		(
			'name' => $this['name'],
			'max-file-size' => $limit * 1024,
			'upload-url' => $this[self::T_UPLOAD_URL]
		);
	}
}