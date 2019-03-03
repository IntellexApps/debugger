<?php

/**
 * @var Trace $trace The Trace to show to the user.
 */

// Style
use Intellex\Debugger\Trace;

$wide = \Intellex\Debugger\Config::getWidthForPlain();
$chars = [
	[ "╒", "═", "╕" ],
	[ "├", "─", "┤" ],
	[ "│", " ", "│" ],
	[ "╘", "═", "╛" ]
];

// Set the template
$tab = $wide - 3;
$template = $chars[0][0] . str_repeat($chars[0][1], $wide - 2) . $chars[0][2] . "\n";
$template .= $chars[2][0] . str_repeat(' ', $wide - 2) . $chars[2][2] . "\n";
$template .= $chars[2][0] . ' ' . "%-{$tab}s" . $chars[2][2] . "\n";
if ($trace->getThrowable()->getMessage()) {
	$template .= $chars[2][0] . str_repeat(' ', $wide - 2) . $chars[2][2] . "\n";
	$template .= $chars[2][0] . ' ' . "%-{$tab}s" . $chars[2][2] . "\n";
} else {
	$template .= "%s";
}
$template .= $chars[2][0] . str_repeat(' ', $wide - 2) . $chars[2][2] . "\n";
$template .= $chars[1][0] . str_repeat($chars[1][1], $wide - 2) . $chars[1][2] . "\n";
$template .= $chars[2][0] . str_repeat(' ', $wide - 2) . $chars[2][2] . "\n";
$template .= "%s\n";
$template .= $chars[2][0] . str_repeat(' ', $wide - 2) . $chars[2][2] . "\n";
$template .= $chars[3][0] . str_repeat($chars[3][1], $wide - 2) . $chars[3][2] . "\n";

// Create message
foreach ($trace->getSteps() as $i => $step) {

	// Show the origin of the error
	if ($step->getFile()) {
		$lines[] = $step->getFile() . ($step->getLine() > 0 ? ' : ' . $step->getLine() : null);
		$lines[] = null;
	}

	// Get the places for line numbers, so code is indented the same, regardless of the line
	$places = $step->getSnippet() ? ceil(log(max(array_keys($step->getSnippet())), 10)) : 0;

	// Print out the snippets
	$lineCount = sizeof($step->getSnippet());
	foreach ($step->getSnippet() as $lineNumber => $line) {

		// Print only the important lines
		if ($step->getLine() && $lineNumber >= $step->getLine() - \Intellex\Debugger\Config::getContextLines() && $lineNumber <= $step->getLine() + \Intellex\Debugger\Config::getContextLines()) {

			// Mark the line where the error has occurred
			$sign = $lineNumber === $step->getLine()
				? '-->'
				: '   ';

			// Indent and generate line
			$linePrefix = sprintf("% {$places}d", $lineNumber);
			$lines[] = " {$sign} {$linePrefix} {$line}";
		}
	}
	$lines[] = null;
}

// Lines
foreach ($lines as $i => $val) {
	$lines[$i] = $chars[2][0] . ' ' . $lines[$i];
	$lines[$i] .= str_repeat(' ', max(1, $wide + 1 - strlen($lines[$i])));
	$lines[$i] .= $chars[2][2];
}

// Print plain text
printf($template, get_class($trace->getThrowable()), $trace->getThrowable()->getMessage(), implode("\n", $lines));

