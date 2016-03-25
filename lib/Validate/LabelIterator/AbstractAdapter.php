<?php

/*
 * This file is part of the Brickrouge package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brickrouge\Validate\LabelIterator;

use Brickrouge\Validate\LabelIterator;

/**
 * Abstract label resolver.
 */
abstract class AbstractAdapter implements LabelIterator
{
	/**
	 * @var mixed $source
	 */
	protected $source;

	/**
	 * @param mixed $source
	 */
	public function __construct($source)
	{
		$this->source = $source;
	}
}
