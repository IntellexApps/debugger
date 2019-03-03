<?php

// Define cases
$cases = [
	[ null,    \Intellex\Debugger\Helper::isCli() ? 'plain' : 'html' ],
	[ 'html',  'html' ],
	[ 'json',  'json' ],
	[ 'plain', 'plain' ],
];

// Run tests
test($cases, function ($input) {
	if ($input !== null) {
		\Intellex\Debugger\Config::setTemplate($input);
	}
	return \Intellex\Debugger\Config::getTemplate();
});

// Revert
\Intellex\Debugger\Config::setTemplate(null);
