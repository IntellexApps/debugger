<?php namespace Intellex\Debugger;

/**
 * Class TraceStep holds a single step in a back trace.
 *
 * @package Intellex\Debugger
 */
class TraceStep implements \JsonSerializable {

	/** @var string The current call type. If a method call, "->" is returned. If a static method call, "::" is returned. If a function call, nothing is returned. */
	private $type;

	/** @var object|null The reference to the object, or null if step was outside of an object. */
	private $object;

	/** @var string|null The name of the class, or null if step was outside of an object. */
	private $class;

	/** @var string|null The name of the function/method, or null if step was outside of one. */
	private $function;

	/** @var array|null TIf inside a function, this lists the functions arguments. If inside an included file, this lists the included file name(s). */
	private $args;

	/** @var string The absolute path to the file, or null if not applicable (ie. internal call). */
	private $file;

	/** @var int The line number in file (staring from 1), or null if not applicable (ie. internal call). */
	private $line;

	/** @var string[] The content of the file (index starting from 1), or null if not applicable (ie. internal call). */
	private $snippet;

	/**
	 * TraceStep constructor.
	 *
	 * @param array    $step             The step to create the TraceStep from,
	 *                                   {@see debug_backtrace()}.
	 * @param int|null $contextLineCount The number of lines around the failing line to preserve,
	 *                                   or null to show the whole file.
	 */
	public function __construct(array $step, $contextLineCount = null) {
		foreach ([ 'type', 'object', 'class', 'function', 'args', 'file', 'line' ] as $key) {
			$this->$key = key_exists($key, $step) ? $step[$key] : null;
		}

		// Make sure the arguments are of a proper type
		if (empty($this->args)) {
			$this->args = !empty($this->function) ? [] : null;
		}

		// Handle snippet
		$this->snippet = [];
		if ($this->file && is_file($this->file) && is_readable($this->file)) {
			$snippet = explode("\n", file_get_contents($this->file));

			// Only save some number of lines around failing file
			$snippetLines = sizeof($snippet);
			if ($contextLineCount) {
				$from = max(0, $this->line - $contextLineCount - 1);
				$to = min($snippetLines, $this->line + $contextLineCount);
				$snippet = array_slice($snippet, $from, $to - $from);

			} else {
				$from = 0;
			}

			// Replace tabulators with double space characters and index the lines properly
			foreach ($snippet as $i => $line) {
				$this->snippet[$from + $i + 1] = str_replace("\t", '  ', rtrim($line, "\r"));
			}
		}
	}

	/** @return string The current call type. If a method call, "->" is returned. If a static method call, "::" is returned. If a function call, nothing is returned. */
	public function getType() {
		return $this->type;
	}

	/** @return object|null The reference to the object, or null if step was outside of an object. */
	public function getObject() {
		return $this->object;
	}

	/** @return string|null The name of the class, or null if step was outside of a class. */
	public function getClass() {
		return $this->class;
	}

	/** @return string|null The name of the function/method, or null if step was outside of one. */
	public function getFunction() {
		return $this->function;
	}

	/** @return array|null The arguments for the function/method, or null if step was outside of one. */
	public function getArgs() {
		return $this->args;
	}

	/** @return string The absolute path to the file, or null if not applicable (ie. internal call). */
	public function getFile() {
		return $this->file;
	}

	/** @return int The line number in file (staring from 1), or null if not applicable (ie. internal call). */
	public function getLine() {
		return $this->line;
	}

	/** @return string[] The content of the file (index starting from 1), or null if not applicable (ie. internal call). */
	public function getSnippet() {
		return $this->snippet;
	}

	/** @inheritdoc */
	public function jsonSerialize() {

		// Get the proper naming
		$function = $this->getFunction();
		if ($this->type !== null) {
			switch ($this->type) {
				case '->':
					$function = $this->getObject() . '->' . $function;
					break;

				default:
					$function = $this->getClass() . $this->type . $function;
			}
		}

		return [
			'function' => $function,
			'args'     => $this->getArgs(),
			'file'     => $this->getFile(),
			'line'     => $this->getLine(),
			'snippet'  => $this->getSnippet()
		];
	}
}
