<?php

// Define cases
$cases = [
	[ 0,                   'UNKNOWN ERROR' ],
	[ E_ERROR,             'ERROR' ],
	[ E_WARNING,           'WARNING' ],
	[ E_PARSE,             'PARSE' ],
	[ E_NOTICE,            'NOTICE' ],
	[ E_CORE_ERROR,        'CORE ERROR' ],
	[ E_CORE_WARNING,      'CORE WARNING' ],
	[ E_COMPILE_ERROR,     'COMPILE ERROR' ],
	[ E_COMPILE_WARNING,   'COMPILE WARNING' ],
	[ E_USER_ERROR,        'USER ERROR' ],
	[ E_USER_WARNING,      'USER WARNING' ],
	[ E_USER_NOTICE,       'USER NOTICE' ],
	[ E_STRICT,            'STRICT' ],
	[ E_RECOVERABLE_ERROR, 'RECOVERABLE ERROR' ],
	[ E_DEPRECATED,        'DEPRECATED' ],
	[ E_USER_DEPRECATED,   'USER DEPRECATED' ]
];

// Run tests
test($cases, function ($input) {
	return \Intellex\Debugger\Helper::getErrorName($input);
});
