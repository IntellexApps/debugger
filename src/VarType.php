<?php namespace Intellex\Debugger;

/**
 * Class VarType holds the list of all possible variable types and a parser for it.
 *
 * @package Intellex\Debugger
 */
abstract class VarType {

	/** @const string A null value. */
	const NULL_ = 'null';

	/** @const string A boolean type. */
	const BOOLEAN_ = 'boolean';

	/** @const string An integer type. */
	const INTEGER_ = 'integer';

	/** @const string A float or double type. */
	const FLOAT_ = 'float';

	/** @const string A string type. */
	const STRING_ = 'string';

	/** @const string An array type. */
	const ARRAY_ = 'array';

	/** @const string An object type. */
	const OBJECT_ = 'object';

	/** @const string A resource type. */
	const RESOURCE_ = 'resource';

	/** @const string An unknown resource type. */
	const UNKNOWN_ = 'unknown';

	/**
	 * Get the variable type of the supplied variable.
	 *
	 * @param mixed $var The variable to check the type.
	 *
	 * @return string The type of the supplied variable.
	 */
	public static function of($var) {
		switch(true) {
			case is_null($var):		return static::NULL_;
			case is_resource($var):	return static::RESOURCE_;
			case is_bool($var):		return static::BOOLEAN_;
			case is_float($var):
			case is_double($var):	return static::FLOAT_;
			case is_integer($var):	return static::INTEGER_;
			case is_string($var):	return static::STRING_;
			case is_array($var):	return static::ARRAY_;
			case is_object($var):	return static::OBJECT_;
		}

		return static::UNKNOWN_;
	}

}
