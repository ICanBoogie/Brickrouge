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

use Brickrouge\Validate\ErrorCollectionReader;

/**
 * Abstract error collection adapter.
 */
abstract class AbstractAdapter implements ErrorCollectionReader, \ArrayAccess, \IteratorAggregate
{
	/**
	 * @var mixed
	 */
	protected $source;

	/**
	 * @param mixed $source
	 */
	public function __construct($source)
	{
		$this->source = $source;
	}

	/**
	 * @inheritdoc
	 */
	public function offsetSet($offset, $value)
	{
		throw new \BadMethodCallException;
	}

	/**
	 * @inheritdoc
	 */
	public function offsetUnset($offset)
	{
		throw new \BadMethodCallException;
	}
}
