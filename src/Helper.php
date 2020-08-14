<?php namespace Intellex\Debugger;

/**
 * Class Helper provide utility methods for the debugger and incident handler.
 *
 * @package Intellex\Debugger
 */
class Helper {

	/** @const string The delimiter for formatting method. */
	const HTML_CODE_DELIMITER = " /**############################**/ \n";

	/** @const string The encoded delimiter for formatting method. */
	const HTML_CODE_DELIMITER_ENCODED = '/**############################**/';

	/**
	 * Measure the length of the string using mb_strlen if multi byte library is present, or fall
	 * back to the normal strlen function.
	 *
	 * @param string $string The string to calculate the length of.
	 *
	 * @return int The length of the string, using the appropriate function.
	 */
	public static function len($string) {
		$stringLengthFunction = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
		return $stringLengthFunction($string);
	}

	/**
	 * Shorten the input to a max length, with a ellipsis after it.
	 *
	 * @param string $string    The original input to shorten.
	 * @param int    $maxLength The maximum length of the resulting string (including ellipsis).
	 * @param string $ellipsis  The ellipsis to use if the string is too long.
	 *
	 * @return string Original string is not longer than the supplied maximum length, or a
	 *                shortened string (with ellipsis) that is of the supplied maximum length.
	 */
	public static function trim($string, $maxLength, $ellipsis = '…') {
		if ($maxLength && static::len($string) > $maxLength) {
			$method = function_exists('mb_substr') ? 'mb_substr' : 'substr';
			$string = $method($string, 0, $maxLength - static::len($ellipsis)) . $ellipsis;
		}

		return $string;
	}

	/**
	 * Get the error name as string.
	 *
	 * @param int $error The integer representing the error.
	 *
	 * @return string The human-friendly name of the error.
	 */
	public static function getErrorName($error) {
		$map = [
			E_ERROR             => 'ERROR',
			E_WARNING           => 'WARNING',
			E_PARSE             => 'PARSE',
			E_NOTICE            => 'NOTICE',
			E_CORE_ERROR        => 'CORE ERROR',
			E_CORE_WARNING      => 'CORE WARNING',
			E_COMPILE_ERROR     => 'COMPILE ERROR',
			E_COMPILE_WARNING   => 'COMPILE WARNING',
			E_USER_ERROR        => 'USER ERROR',
			E_USER_WARNING      => 'USER WARNING',
			E_USER_NOTICE       => 'USER NOTICE',
			E_STRICT            => 'STRICT',
			E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
			E_DEPRECATED        => 'DEPRECATED',
			E_USER_DEPRECATED   => 'USER DEPRECATED'
		];

		return key_exists($error, $map)
			? $map[$error]
			: 'UNKNOWN ERROR';
	}

	/**
	 * Get a human-friendly readable value of the supplied variable.
	 *
	 * @param mixed    $var           The variable to get the readable value.
	 * @param bool     $useDebugPrint True to use the __debug() method of the class, if available.
	 * @param int|null $maxLength     The maximum length of the variable, or null for unlimited.
	 *
	 * @return string The human-friendly value of the supplied value.
	 */
	public static function getReadableValue($var, $useDebugPrint = true, $maxLength = null, $indent = 0) {

		// Get the proper string representation, based on the type of the variable
		$type = null;
		switch (VarType::of($var)) {
			case VarType::NULL_:
				$var = '(null value)';
				break;

			case VarType::BOOLEAN_:
				$var = $var ? 'true' : 'false';
				$type = 'boolean';
				break;

			case VarType::INTEGER_:
				$type = 'integer';
				break;

			case VarType::FLOAT_:
				$type = 'float';
				break;

			case VarType::STRING_:
				if (empty($var) || is_numeric($var) || trim($var) !== $var) {
					$type = 'string(' . Helper::len($var) . ')';
					$var = '"' . $var . '"';
				}
				break;

			/** @noinspection PhpMissingBreakStatementInspection */
			case VarType::OBJECT_:

				// Check for debug info
				if ($useDebugPrint && method_exists($var, '__debug')) {
					$var = $var->__debug();
					break;
				}

			case VarType::ARRAY_:

				// Make sure to print just class name for huge objects
				if (static::isSerializable($var)) {
					if ($maxLength && Helper::len(serialize($var)) > $maxLength) {
						$var = 'HUGE ' . (is_array($var) ? 'Array' : get_class($var));
					}
				} else {
					$var = 'Cannot be serialized';
				}

				// Handle arrays
				if (is_array($var)) {
					foreach ($var as $i => $value) {
						$var[$i] = static::getReadableValue($value, $useDebugPrint, $maxLength, $indent + 4);
					}
				}

				$var = str_replace("\n", "\n" . str_repeat("  ", $indent), trim(print_r($var, true))) . "\n";
				break;

			case VarType::RESOURCE_:
				$var = '#' . intval($var);
				$type = 'resource';
				break;

			default:
				$var = '(unknown variable type)';
		}

		$template = $type ? "{$type}: %s" : '%s';
		return sprintf($template, Helper::trim($var, $maxLength));
	}

	/**
	 * Format the supplied PHP code, as HTML readable string.
	 *
	 * @param string $code   The code to format.
	 * @param string $select The line to highlight, starting at index 0.
	 * @param string $from   The first line to print, starting at index 0.
	 * @param string $to     The last line to print, starting at index 0.
	 *
	 * @return string  The human-friendly formatted code.
	 */
	public static function formatCodeAsHTML($code, $select = null, $from = null, $to = null) {

		// Normalize input
		$code = is_array($code) ? $code : explode("\n", $code);
		$count = sizeof($code);
		$from = $from ? max($from, 0) : 0;
		$to = $to ? min($to + 1, $count) : $count;

		// Set custom markers
		$markers = [ 'comment', 'default', 'html', 'keyword', 'string' ];
		foreach ($markers as $marker) {
			ini_set("highlight.{$marker}", "Debugger::highlight.{$marker}");
		}

		// Highlight the code and replace with custom class
		$text = implode(static::HTML_CODE_DELIMITER, $code);
		$text = highlight_string($text, true);
		foreach ($markers as $marker) {
			$text = str_replace("<span style=\"color: Debugger::highlight.{$marker}\">", "<span class=\"php-{$marker}\">", $text);
		}

		// Remove '<?php' from the beginning
		$text = str_replace('&lt;?php&nbsp;', '', $text);

		// Set numbers
		$output = [];
		$lines = explode(static::HTML_CODE_DELIMITER_ENCODED, $text);
		for ($i = $from; $i < $to; $i++) {
			$n = $i + 1;
			$line = preg_replace('~^&nbsp;<br\s*/?>~', '', $lines[$i]);
			$line = preg_replace('~</?code>~', '', $line);
			$number = $n . str_repeat('&nbsp;', strlen($to) - strlen($n));
			$output[$n] = "<span class=\"php-line\">{$number}</span>&nbsp;{$line}";

			$output[$n] = '<div class="' . ($n === $select ? 'highlighted ' : null) . 'line">' . $output[$n] . '</div>';
		}
		$text = implode(PHP_EOL, $output);

		return '<div class="intellex-debugger-code">' . $text . '</div>';
	}

	/**
	 * Check if a variable can be serialized.
	 *
	 * @param mixed $var     The variable to check.
	 * @param bool  $iterate True to check for all elements inside the variable as well.
	 *
	 * @return bool True if the variable can be serialized, false otherwise.
	 */
	public static function isSerializable($var, $iterate = true) {

		// Resources cannot be serialized
		if (is_resource($var)) {
			return false;
		}

		// Some objects cannot be serialized as well
		if (is_object($var)) {
			if ($var instanceof Closure || (!$var instanceof Serializable && !$var instanceof ArrayAccess)) {
				return false;
			}
		}

		// Make sure all elements are serializable
		if ($iterate && is_iterable($var)) {
			foreach ($var as $key => $value) {
				if (!static::isSerializable($value, true)) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Check if the script is executed from cli.
	 *
	 * @return boolean True if we are in console line interface, false otherwise.
	 */
	public static function isCli() {
		return in_array(php_sapi_name(), [ 'cli', 'cgi-fcgi' ]);
	}

	/**
	 * Check if the current request is AJAX or not.
	 *
	 * @return boolean True if this call is ajax, false otherwise.
	 */
	public static function isAjax() {
		return key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}

}
