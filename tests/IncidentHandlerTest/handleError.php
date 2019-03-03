<?php

// Define cases
$cases = [
	[ [ null, null, null, null ], [ 500, 'UNKNOWN ERROR', '', -1 ] ],
	[ [ -1, '', '', 10 ], [ 500, 'UNKNOWN ERROR', '', -1 ] ],
	[ [ -1, 'Oops!', null, 99 ], [ 500, 'UNKNOWN ERROR: Oops!', '', -1 ] ],
	[ [ -1, '', 'file', 9 ], [ 500, 'UNKNOWN ERROR', 'file', 9 ] ],
	[ [ E_ERROR, 'Error', 'file', 2 ], [ 500, 'ERROR: Error', 'file', 2 ] ],
	[ [ E_ERROR, 'Error', 'file', 2 ], [ 500, 'ERROR: Error', 'file', 2 ] ],
	[ [ E_STRICT, 'Not allowed!', 'Anywhere', 0 ], [ 500, 'STRICT: Not allowed!', 'Anywhere', 0 ] ],
	[ [ E_DEPRECATED, 'Old school...', 'C:\\www\\debugger\index.php', 2443 ], [ 500, 'DEPRECATED: Old school...', 'C:\\www\\debugger\index.php', 2443 ] ],
	[ [ E_COMPILE_ERROR, 'File too big', 'upload.php', 43 ], [ 500, 'COMPILE ERROR: File too big', 'upload.php', 43 ] ],
];

// Run tests
test($cases, function ($input) {
	try {
		forward_static_call_array('\Intellex\Debugger\IncidentHandler::handleError', $input);
	} catch (ErrorException $ex) {
		return [ $ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine() ];
	}

	return [];
});
