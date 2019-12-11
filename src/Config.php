<?php namespace Intellex\Debugger;

/**
 * Class Config holds the configuration for the debugger and the incident handler.
 *
 * @package Intellex\Debugger
 */
class Config {

	/** @var int The number of lines to show around the line that raised the error. */
	private static $contextLines = 8;

	/** @var int The maximum size of an variable to display, in bytes. */
	private static $maxDumpSize = 2097152;

	/** @var int The width for the plain format. */
	private static $widthForPlain = 140;

	/** @var string|null The forced template to use, or null to auto select the template. */
	private static $template = null;

	/** @param int $contextLines The number of lines to show around the line that raised the error. */
	public static function setContextLines($contextLines) {
		static::$contextLines = $contextLines;
	}

	/** @return int The number of lines to show around the line that raised the error. */
	public static function getContextLines() {
		return static::$contextLines;
	}

	/** @param int $maxDumpSize The maximum size of an variable to display, in bytes. */
	public static function setMaxDumpSize($maxDumpSize) {
		static::$maxDumpSize = $maxDumpSize;
	}

	/** @return int The maximum size of an variable to display, in bytes. */
	public static function getMaxDumpSize() {
		return static::$maxDumpSize;
	}

	/** @param int $widthForPlain The width for the plain format. */
	public static function setWidthForPlain($widthForPlain) {
		self::$widthForPlain = $widthForPlain;
	}

	/** @return int The width for the plain format. */
	public static function getWidthForPlain() {
		return self::$widthForPlain;
	}

	/** @param string|null The forced template to use, or null to auto select the template. */
	public static function setTemplate($template) {
		static::$template = $template;
	}

	/** @return string|null The forced template to use, or null to auto select the template. */
	public static function getTemplate() {

		// Make sure we do not have forced template
		if (static::$template !== null) {
			return static::$template;
		}

		// Select template based on the request headers
		if (isset($_SERVER) && key_exists('HTTP_ACCEPT', $_SERVER)) {
			$map = [
				'/html\b' => [ 'html' ],
				'/json\b' => [ 'json', 'json_encode' ],
			];
			foreach ($map as $regexp => $template) {
				if (preg_match("~{$regexp}~ i", $_SERVER['HTTP_ACCEPT'])) {

					// Check for the requirement
					if (key_exists(1, $template) && !function_exists($template[1])) {
						break;
					}

					return $template[0];
				}
			}
		}

		// Fallback to plain text
		return 'plain';

	}

}
