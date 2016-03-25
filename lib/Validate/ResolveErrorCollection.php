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

use ICanBoogie\Validate\ValidationErrors;

class ResolveErrorCollection
{
	/**
	 * @param mixed $errors
	 *
	 * @return ErrorCollectionReader
	 */
	public function __invoke($errors)
	{
		if ($errors instanceof ErrorCollectionReader)
		{
			return $errors;
		}

		if ($errors instanceof ValidationErrors)
		{
			return new ErrorCollectionReader\ValidationErrorsAdapter($errors);
		}

		if ($errors instanceof \ICanBoogie\Errors)
		{
			return new ErrorCollectionReader\ErrorsAdapter($errors);
		}

		if (is_array($errors))
		{
			return new ErrorCollectionReader\ArrayAdapter($errors);
		}

		throw new \InvalidArgumentException("Don't know any adapter for: " . get_class($errors));
	}
}
