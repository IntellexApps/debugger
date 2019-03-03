<?php

// Define cases
$cases = [
	[ null, 8 ],
	[ 0, 0 ],
	[ 100, 100 ],
];

// Run tests
$original = \Intellex\Debugger\Config::getContextLines();
test($cases, function ($input) {
	if ($input !== null) {
		\Intellex\Debugger\Config::setContextLines($input);
	}
	return \Intellex\Debugger\Config::getContextLines();
});

// Revert
\Intellex\Debugger\Config::setContextLines($original);
