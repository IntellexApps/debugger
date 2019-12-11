<?php

/**
 * @var mixed $data          The data to present to user.
 * @var bool  $useDebugPrint True to use the __debug() method of the class, if available.
 */

// Style
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
$template .= $chars[2][0] . ' ' . "%-{$tab}s" . $chars[2][2] . "\n";
$template .= $chars[1][0] . str_repeat($chars[1][1], $wide - 2) . $chars[1][2] . "\n";
$template .= $chars[2][0] . str_repeat(' ', $wide - 2) . $chars[2][2] . "\n";
$template .= "%s\n";
$template .= $chars[2][0] . str_repeat(' ', $wide - 2) . $chars[2][2] . "\n";
$template .= $chars[3][0] . str_repeat($chars[3][1], $wide - 2) . $chars[3][2] . "\n";

// Create
$lines = explode("\n", \Intellex\Debugger\Helper::getReadableValue($data['value'], $useDebugPrint));

// Lines
foreach ($lines as $i => $val) {
	$lines[$i] = $chars[2][0] . ' ' . $lines[$i];
	$lines[$i] .= str_repeat(' ', max(1, $wide + 1 - strlen($lines[$i])));
	$lines[$i] .= $chars[2][2];
}

// Print
printf($template, $data['file'] . ' : ' . $data['line'], implode("\n", $lines));
