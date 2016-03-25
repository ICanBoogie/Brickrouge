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

use Brickrouge\Validate\ErrorCollectionReader\AbstractAdapter;
use ICanBoogie\Validate\Message;
use ICanBoogie\Validate\ValidationErrors;

/**
 * Error collection adapter for {@link ValidationErrors} instances.
 */
class ValidationErrorsAdapter extends AbstractAdapter
{
	/**
	 * @var ValidationErrors
	 */
	protected $source;

	/**
	 * @param ValidationErrors $source
	 */
	public function __construct(ValidationErrors $source)
	{
		parent::__construct($source);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetExists($attribute)
	{
		return isset($this->source[$attribute]);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetGet($attribute)
	{
		$rc = [];

		/* @var $messages Message[] */

		foreach ($this->source[$attribute] as $message)
		{
			$rc[] = [ $message->format, $message->args ];
		}

		return $rc;
	}

	public function getIterator()
	{
		/* @var $messages Message[] */

		foreach ($this->source as $attribute => $messages)
		{
			foreach ($messages as $message)
			{
				yield $attribute => [ $message->format, $message->args ];
			}
		}
	}
}
