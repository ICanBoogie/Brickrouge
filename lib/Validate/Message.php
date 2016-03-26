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

use ICanBoogie\Accessor\AccessorTrait;
use function ICanBoogie\format;

/**
 * @property-read string $format
 * @property-read array $args
 */
class Message implements \JsonSerializable
{
	use AccessorTrait;

	/**
	 * @var string
	 */
	private $format;

	/**
	 * @return string
	 */
	protected function get_format()
	{
		return $this->format;
	}

	/**
	 * @var array
	 */
	private $args;

	/**
	 * @return array
	 */
	protected function get_args()
	{
		return $this->args;
	}

	/**
	 * @param string $format
	 * @param array $args
	 */
	public function __construct($format, array $args = [])
	{
		$this->format = $format;
		$this->args = $args;
	}

	/**
	 * @inheritdoc
	 */
	public function __toString()
	{
		return format($this->format, $this->args);
	}

	/**
	 * @inheritdoc
	 */
	function jsonSerialize()
	{
		return (string) $this;
	}
}
