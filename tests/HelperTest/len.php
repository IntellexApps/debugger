<?php

// Define cases
$cases = [
	[ '',        0 ],
	[ ' ',       1 ],
	[ '_',       1 ],
	[ '123',     3 ],
	[ 'Šiš-miš', function_exists('mb_strlen') ? 7 : 10 ],
];

// Run tests
test($cases, function ($input) {
	return \Intellex\Debugger\Helper::len($input);
});
