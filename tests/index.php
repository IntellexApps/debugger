<?php require '../vendor/autoload.php';

\Intellex\Debugger\IncidentHandler::register();

// Define test method
function test($cases, $callback) {

	// Get the trace
	$trace = debug_backtrace();
	preg_match('~/(\w+)/(\w+)\.php$~', $trace[0]['file'], $source);

	// Run test
	foreach ($cases as $i => $data) {

		// Load the cases
		list($input, $expected) = $data;
		$result = $callback($input);

		// If failed
		if (!isset($result) || $result !== $expected) {
			echo "\nFailed test of {$source[1]}::{$source[2]}()\n";

			echo "\nInput: ";
			print_r($input);

			echo "\nExpected: ";
			print_r($expected);

			echo "\nReceived: ";
			print_r($result);

			exit(1);
		}
	}
}

// Load and run all tests
$index = basename(__FILE__);
$classes = glob('./*Test');
foreach ($classes as $class) {
	$files = glob("{$class}/*.php");
	foreach ($files as $file) {
		/** @noinspection PhpIncludeInspection */
		require $file;
	}
}

echo 'All tests passed' . PHP_EOL;
exit(0);
