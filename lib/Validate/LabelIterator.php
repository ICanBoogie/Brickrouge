<?php

namespace Brickrouge\Validate;

interface LabelIterator extends \IteratorAggregate
{
	/**
	 * Iterate over labels.
	 *
	 * @return array An array of name/label pairs.
	 */
	public function getIterator();
}
