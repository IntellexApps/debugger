<?php

use Intellex\Debugger\Helper;

// Define cases
$cases = [

	// Null
	[ null,             '(null value)' ],

	// Boolean
	[ true,             'boolean: true' ],
	[ false,            'boolean: false' ],
	[ !1,               'boolean: false' ],
	[ empty([]),        'boolean: true' ],

	// Integer
	[ -1,               'integer: -1' ],
	[ -0,               'integer: 0' ],
	[ 0,                'integer: 0' ],
	[ 1,                'integer: 1' ],
	[ 11,               'integer: 11' ],
	[ 10+7,             'integer: 17' ],
	[ 25/5,             'integer: 5' ],
	[ 25*5,             'integer: 125' ],

	// Float
	[ -1.0,             'float: -1' ],
	[ -0.0,             'float: -0' ],
	[ 0.0,              'float: 0' ],
	[ 1.0,              'float: 1' ],
	[ 11.0,             'float: 11' ],
	[ 10.0+7.0,         'float: 17' ],
	[ 25.0/5,           'float: 5' ],
	[ 25*5.0,           'float: 125' ],
	[ 12/5,             'float: 2.4' ],
	[ ceil(10/3), 'float: 4' ],

	// String
	[ '',               'string(0): ""' ],
	[ ' ',              'string(1): " "' ],
	[ "\t\n" ,          'string(2): "' . "\t\n" . '"' ],
	[ "line\n" ,        'string(5): "line' . "\n" . '"' ],
	[ "new\nline" ,     'new' . "\n" . 'line' ],
	[ '\n',             '\n' ],
	[ 1 . 1 ,           '11' ],

	// Object
	[ new stdClass(),  "stdClass Object\n(\n)\n" ],
	[ new Helper(),    "Intellex\Debugger\Helper Object\n(\n)\n" ],

	// Array
	[ [ ],                           "Array\n(\n)\n" ],
	[ [ 'Off', 'On' ],               "Array\n(\n    [0] => Off\n    [1] => On\n)\n" ],
	[ [ 'key' => [ 'value' => 2 ] ], "Array\n(\n    [key] => Array\n        (\n            [value] => 2\n        )\n\n)\n" ],

];

// Run tests
test($cases, function ($input) {
	return Helper::getReadableValue($input);
});
