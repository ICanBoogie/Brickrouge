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

use function Brickrouge\t;

/**
 * Representation of validation errors.
 */
class Errors implements \IteratorAggregate, \ArrayAccess
{
	/**
	 * @param mixed $errors
	 * @param mixed $labels
	 *
	 * @return static
	 */
	static public function from($errors, $labels)
	{
		if (!$errors)
		{
			return null;
		}

		$errors = static::resolve_errors_adapter($errors);
		$labels = static::resolve_label_iterator($labels);

		return new static($errors, $labels);
	}

	/**
	 * @param $errors
	 *
	 * @return ErrorCollectionReader
	 */
	static protected function resolve_errors_adapter($errors)
	{
		static $resolver;

		if (!$resolver)
		{
			$resolver = new ResolveErrorCollection;
		}

		return $resolver($errors);
	}

	/**
	 * @param mixed $labels
	 *
	 * @return LabelIterator
	 */
	static protected function resolve_label_iterator($labels)
	{
		static $resolver;

		if (!$resolver)
		{
			$resolver = new ResolveLabelIterator;
		}

		return $resolver($labels);
	}

	/**
	 * @var ErrorCollectionReader
	 */
	private $errors;

	/**
	 * @var LabelIterator|callable
	 */
	private $label_iterator;

	/**
	 * @var array
	 */
	private $labels;

	/**
	 * @param ErrorCollectionReader $errors
	 * @param LabelIterator $resolve_iterator
	 */
	public function __construct(ErrorCollectionReader $errors, LabelIterator $resolve_iterator)
	{
		$this->errors = $errors;
		$this->label_iterator = $resolve_iterator;
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator()
	{
		foreach ($this->collect_messages() as $name => $messages)
		{
			foreach ($messages as $message)
			{
				yield $name => $message;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function offsetExists($offset)
	{
		return isset($this->errors[$offset]);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetGet($name)
	{
		$messages = [];

		foreach ($this->errors[$name] as list($format, $args))
		{
			$messages[] = $this->format_message($name, $format, $args);
		}

		return $messages;
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

	/**
	 * @return array
	 */
	protected function collect_messages()
	{
		$messages = [];

		foreach ($this->errors as $name => $message)
		{
			if (is_array($message))
			{
				list($format, $args) = $message;
			}
			else if ($message instanceof Message)
			{
				$format = $message->format;
				$args = $message->args;
			}
			else
			{
				throw new \InvalidArgumentException("message should either be an array or a Message instance");
			}

			$messages[$name][] = $this->format_message($name, $format, $args);
		}

		return array_intersect_key(array_merge($this->get_labels(), $messages), $messages);
	}

	/**
	 * Formats a message.
	 *
	 * @param string $name
	 * @param string $format
	 * @param array $args
	 *
	 * @return string
	 */
	protected function format_message($name, $format, array $args)
	{
		return $this->translate_message($format, $args + [

			'name' => $name,
			'label' => $this->translate_label($this->resolve_label($name))

		]);
	}

	protected function translate_message($format, $args)
	{
		return t($format, $args, [ 'scope' => 'validation' ]);
	}

	/**
	 * @param string|null $label
	 *
	 * @return string|null
	 */
	protected function translate_label($label)
	{
		if (!$label)
		{
			return null;
		}

		return t($label, [], [ 'scope' => 'element.label' ]);
	}

	/**
	 * @param string $name
	 *
	 * @return string|null
	 */
	protected function resolve_label($name)
	{
		$labels = $this->get_labels();

		return isset($labels[$name]) ? $labels[$name] : null;
	}

	/**
	 * @return array
	 */
	protected function get_labels()
	{
		$labels = &$this->labels;

		if ($labels === null)
		{
			$labels = [];

			foreach ($this->label_iterator as $name => $label)
			{
				$labels[$name] = $label;
			}
		}

		return $labels;
	}
}
