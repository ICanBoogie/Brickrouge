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

interface ErrorCollectionReader
{
	/**
	 * Checks if errors are defined for an attribute.
	 *
	 * @param string $attribute
	 *
	 * @return bool
	 */
	public function offsetExists($attribute);

	/**
	 * @param $attribute
	 *
	 * @return array
	 */
	public function offsetGet($attribute);

	/**
	 * @return array
	 */
	public function getIterator();
}
