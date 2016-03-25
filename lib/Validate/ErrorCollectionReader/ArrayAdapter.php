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
use Brickrouge\Validate\Message;

/**
 * Error collection adapter for arrays.
 */
class ArrayAdapter extends AbstractAdapter
{
	/**
	 * @var array
	 */
	protected $source;

	/**
	 * @param array $source
	 */
	public function __construct(array $source)
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

		$message = $this->source[$attribute];

		if (is_array($message))
		{
			foreach ($this->source[$attribute] as $message)
			{
				$rc[] = $this->resolve_message($message);
			}
		}
		else
		{
			$rc[] = $this->resolve_message($message);
		}

		return $rc;
	}

	public function getIterator()
	{
		foreach ($this->source as $attribute => $message)
		{
			if (is_array($message))
			{
				foreach ($message as $item)
				{
					yield $attribute => $this->resolve_message($item);
				}
			}
			else
			{
				yield $attribute => $this->resolve_message($message);
			}
		}
	}

	protected function resolve_message($message)
	{
		return $message instanceof Message
			? [ $message->format, $message->args ]
			: [ $message === true ? '' : $message, [] ];
	}
}
