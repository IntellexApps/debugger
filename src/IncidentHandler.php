<?php namespace Intellex\Debugger;

/**
 * Class IncidentHandler handles both errors and exceptions.
 *
 * @package Intellex\Debugger
 */
class IncidentHandler {

	/**
	 * Initialize the IncidentHandler.
	 */
	public static function register() {
		error_reporting(E_ALL);
		set_error_handler('\Intellex\Debugger\IncidentHandler::handleError', E_ALL);
		set_exception_handler('\Intellex\Debugger\IncidentHandler::handleException');
		register_shutdown_function('\Intellex\Debugger\IncidentHandler::handleShutdown');
	}

	/**
	 * Handle error.
	 *
	 * @param int $type    The level of the error raised, as an integer.
	 * @param int $message The the error message, as a string.
	 * @param int $file    The filename that the error was raised in, as a string.
	 * @param int $line    The line number the error was raised at, as an integer
	 *
	 * @throws \ErrorException The exception that was generated from the error.
	 */
	public static function handleError($type, $message, $file = null, $line = null) {
		throw static::castErrorToException(compact('type', 'message', 'file', 'line'));
	}

	/**
	 * Handle exception.
	 *
	 * @param \Throwable $throwable The exception to render to user.
	 * @param bool       $exit      Set to true to send error headers and stop the execution.
	 */
	public static function handleException($throwable, $exit = true) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$trace = new Trace($throwable, Config::getContextLines());

		// Send an appropriate header
		if ($exit && !Helper::isCli() && !headers_sent()) {
			header($_SERVER['SERVER_PROTOCOL'] . ' ' . 500 . ' Internal Server Error', true, 500);
		}

		/** @noinspection PhpIncludeInspection */
		require implode(DIRECTORY_SEPARATOR, [ __DIR__, 'templates', 'incident', Config::getTemplate() . '.php' ]);

		// Stop the execution
		if ($exit) {
			exit($throwable->getCode());
		}
	}

	/**
	 * Make sure the parse errors are captured as well.
	 */
	public static function handleShutdown() {
		$error = error_get_last();
		if ($error) {
			static::handleException(static::castErrorToException($error));
		}
	}

	/**
	 * Cast an existing error to an exception.
	 *
	 * @param array $error An error array, as returned from the error_get_last() function.
	 *
	 * @return \ErrorException  The created exception.
	 */
	public static function castErrorToException($error) {

		// Handle the message of the exception
		$message = Helper::getErrorName($error['type']);
		if (!empty($error['message'])) {
			$message .= ': ' . $error['message'];
		}

		// Skip file and line number if file is unknown
		return new \ErrorException(
			$message,
			500,
			$error['type'],
			!empty($error['file']) ? $error['file'] : '',
			!empty($error['file']) ? $error['line'] : -1
		);
	}

}