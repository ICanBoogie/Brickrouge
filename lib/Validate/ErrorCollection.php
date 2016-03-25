<?php

/*
 * This file is part of the Brickrouge package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brickrouge\Validate;

/**
 * An error collection.
 */
class ErrorCollection implements ErrorCollectionReader, \ArrayAccess, \IteratorAggregate, \Countable
{
	/**
	 * Special identifier used when an error is not associated with a specific attribute.
	 */
	const BASE = '_base';

	/**
	 * @var Message[][]
	 */
	private $collection = [];

	/**
	 * @param string $attribute Attribute name.
	 * @param string $message_or_format_or_true A {@link Message} instance or a format to create
	 * that instance, or `true`.
	 * @param array $args Only used if `$message_or_format_or_true` is not a {@link Message}
	 * instance or `true`.
	 */
	public function add($attribute, $message_or_format_or_true, array $args = [])
	{
		$message = $message_or_format_or_true;

		if (!$message instanceof Message)
		{
			$message = new Message(is_bool($message) ? "" : $message, $args);
		}

		$this->collection[$attribute ?: self::BASE][] = $message;
	}

	/**
	 * @inheritdoc
	 */
	public function offsetSet($attribute, $message)
	{
		$this->add($attribute, $message);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetUnset($attribute)
	{
		unset($this->collection[$attribute]);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetExists($attribute)
	{
		return isset($this->collection[$attribute]);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetGet($attribute)
	{
		return $this->collection[$attribute ?: self::BASE];
	}

	/**
	 * @return Message[]
	 */
	public function getIterator()
	{
		foreach ($this->collection as $attribute => $messages)
		{
			foreach ($messages as $message)
			{
				yield $attribute => $message;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function count()
	{
		$n = 0;

		foreach ($this->collection as $messages)
		{
			$n += count($messages);
		}

		return $n;
	}
}
