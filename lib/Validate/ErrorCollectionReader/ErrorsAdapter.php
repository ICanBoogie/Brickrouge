<?php

/*
 * This file is part of the Brickrouge package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brickrouge\Validate\ErrorCollectionReader;

use Brickrouge\Validate\ErrorCollectionReader\ArrayAdapter;
use ICanBoogie\Errors;

/**
 * Error collection adapter for {@link Errors} instances.
 */
class ErrorsAdapter extends ArrayAdapter
{
	/**
	 * @var Errors
	 */
	protected $source;

	/**
	 * @param Errors $source
	 */
	public function __construct(Errors $source)
	{
		/* @var $source array */

		parent::__construct($source);
	}

	public function getIterator()
	{
		foreach ($this->source as $attribute => $message)
		{
			yield $attribute => $this->resolve_message($message);
		}
	}
}
