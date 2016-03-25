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

use Brickrouge\Form;

class ResolveLabelIterator
{
	/**
	 * @param mixed $labels
	 *
	 * @return LabelIterator
	 */
	public function __invoke($labels)
	{
		if ($labels instanceof LabelIterator)
		{
			return $labels;
		}

		if ($labels instanceof Form)
		{
			return new LabelIterator\FormAdapter($labels);
		}

		if (is_array($labels))
		{
			return new LabelIterator\ArrayAdapter($labels);
		}

		throw new \InvalidArgumentException("Don't know any adapter for: " . get_class($labels));
	}
}
