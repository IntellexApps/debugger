<?php namespace Intellex\Debugger;

/**
 * Class VarDump shows the detailed value of any variable.
 *
 * @package Intellex\Debugger
 */
class VarDump {

	/**
	 * Dump the variable data in an appropriate template.
	 *
	 * @param mixed $var       The variable to print.
	 * @param int   $traceSkip An optional number of skips in the trace, in order to avoid same
	 *                         last step (ie. when called from a intermediate helper function).
	 */
	public static function from($var, $traceSkip = 0) {

		// Initialize the data
		$data = [
			'file'  => null,
			'line'  => null,
			'value' => $var
		];

		// From where it was called
		$trace = debug_backtrace();
		/** @noinspection PhpUnusedLocalVariableInspection */
		$data = array_merge($data, $trace[$traceSkip]);

		// Dump the variable in a proper template
		$template = Config::getTemplate();
		$file = implode(DIRECTORY_SEPARATOR, [ __DIR__, 'templates', 'dump', Config::getTemplate() . '.php' ]);
		is_readable($file) or die("Unknown template `{$template}` for Debugger.");

		/** @noinspection PhpIncludeInspection */
		require $file;
	}

}
