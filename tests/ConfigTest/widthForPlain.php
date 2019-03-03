<?php

// Define cases
$cases = [
	[ null, 140 ],
	[ 100, 100 ],
	[ 80, 80 ]
];

// Run tests
$original = \Intellex\Debugger\Config::getWidthForPlain();
test($cases, function ($input) {
	if ($input !== null) {
		\Intellex\Debugger\Config::setWidthForPlain($input);
	}
	return \Intellex\Debugger\Config::getWidthForPlain();
});

// Revert
\Intellex\Debugger\Config::setWidthForPlain($original);
