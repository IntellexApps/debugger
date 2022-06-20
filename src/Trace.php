<?php namespace Intellex\Debugger;

use Throwable;

/**
 * Class Trace holds all the information about the stack trace.
 *
 * @package Intellex\Debugger
 */
class Trace {

	/** @var Throwable The throwable from which the trace was extracted. */
	private $throwable;

	/** @var TraceStep|null The original source of an error. */
	private $origin;

	/** @var TraceStep[] The list of all steps in the back trace. */
	private $steps = [];

	/**
	 * Trace constructor.
	 *
	 * @param Throwable $throwable         The throwable from which the trace was extracted.
	 * @param int|null  $contextLineCount  The number of lines around the failing line to preserve,
	 *                                     or null to show the whole file.
	 */
	public function __construct(Throwable $throwable, $contextLineCount) {
		$this->throwable = $throwable;

		// Extract the origin
		$this->origin = preg_match("~ in (?'file'.+?):(?'line'\d+\b)~ Uui", $throwable->getMessage(), $match)
			? new TraceStep([ 'file' => $match['file'], 'line' => $match['line'] ])
			: null;

		// Extract the back trace
		$steps = $throwable->getTrace();
		foreach ($steps as $step) {

			// Define steps to skip
			$skip = [
				[
					'class'    => 'Intellex\Debugger\IncidentHandler',
					'function' => 'castErrorToException'
				]
			];

			// Filter the unwanted steps
			foreach ($skip as $item) {
				foreach ($item as $key => $value) {
					if (!key_exists($key, $step) || $step[$key] !== $value) {
						continue(2);
					}
				}

				// If reached than this item has been matched
				continue(2);
			}

			// Append the step
			$this->steps[] = new TraceStep($step, $contextLineCount);
		}
	}

	/** @return Throwable The throwable from which the trace was extracted. */
	public function getThrowable(): Throwable {
		return $this->throwable;
	}

	/** @return TraceStep|null The original source of an error. */
	public function getOrigin() {
		return $this->origin;
	}

	/** @return TraceStep[] The list of all steps in the back trace. */
	public function getSteps(): array {
		return $this->steps;
	}

}
