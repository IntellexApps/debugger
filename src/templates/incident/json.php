<?php /** @noinspection PhpComposerExtensionStubsInspection */

use Intellex\Debugger\Trace;

/**
 * @var Trace $trace The Trace to show to the user.
 */

$json = [
	'error' => [
		'message'     => $trace->getThrowable()->getMessage(),
		'trace'       => [],
		'environment' => [
			'REQUEST' => $_REQUEST,
			'SERVER'  => $_SERVER,
			'COOKIE'  => $_COOKIE,
			'FILES'   => $_FILES,
		]
	]
];

// Stack trace
foreach ($trace->getSteps() as $i => $step) {
	$json['error']['trace'][] = $step;
}

// Print JSON
header('Content-Type: application/json');
echo json_encode($json, JSON_PRETTY_PRINT);
