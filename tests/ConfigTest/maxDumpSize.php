<?php

// Define cases
$cases = [
	[ null, 2097152 ],
	[ 0, 0 ],
	[ pow(2, 16), 65536 ],
];

// Run tests
$original = \Intellex\Debugger\Config::getMaxDumpSize();
test($cases, function ($input) {
	if ($input !== null) {
		\Intellex\Debugger\Config::setMaxDumpSize($input);
	}
	return \Intellex\Debugger\Config::getMaxDumpSize();
});

// Revert
\Intellex\Debugger\Config::setMaxDumpSize($original);
