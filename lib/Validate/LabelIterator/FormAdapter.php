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

use Brickrouge\Element;
use Brickrouge\Form;
use Brickrouge\Group;

/**
 * Label resolver adapter for Brickrouge's forms.
 */
class FormAdapter extends AbstractAdapter
{
	/**
	 * @var Form
	 */
	protected $source;

	/**
	 * @param Form $form
	 */
	public function __construct(Form $form)
	{
		parent::__construct($form);
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator()
	{
		/* @var $element Element */

		foreach ($this->source as $element)
		{
			$name = $element['name'];

			if (!$name)
			{
				continue;
			}

			$label = $element[Element::LABEL_MISSING] ?: $element[Element::LABEL] ?: $element[Group::LABEL] ?: $element[Element::LEGEND];

			if (!$label)
			{
				continue;
			}

			yield $name => $label;
		}
	}
}
