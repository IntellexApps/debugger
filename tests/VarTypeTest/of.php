<?php

use Intellex\Debugger\VarType;

// Define cases
$cases = [

	// Null
	[ null,           VarType::NULL_ ],

	// Boolean
	[ true,           VarType::BOOLEAN_ ],
	[ !true,          VarType::BOOLEAN_ ],
	[ false,          VarType::BOOLEAN_ ],
	[ !1,             VarType::BOOLEAN_ ],

	// Integer
	[ -1,             VarType::INTEGER_ ],
	[ -0,             VarType::INTEGER_ ],
	[ 0,              VarType::INTEGER_ ],
	[ 1,              VarType::INTEGER_ ],
	[ PHP_INT_MAX,    VarType::INTEGER_ ],

	// Float
	[ -1.0,           VarType::FLOAT_ ],
	[ -0.1,           VarType::FLOAT_ ],
	[ -0.0,           VarType::FLOAT_ ],
	[ 0.0,            VarType::FLOAT_ ],
	[ 0.1,            VarType::FLOAT_ ],
	[ 1.0,            VarType::FLOAT_ ],

	// String
	[ '',             VarType::STRING_ ],
	[ "",             VarType::STRING_ ],
	[ ' ',            VarType::STRING_ ],
	[ '1',            VarType::STRING_ ],
	[ 'true',         VarType::STRING_ ],
	[ 'null',         VarType::STRING_ ],
	[ "\n",           VarType::STRING_ ],
	[ "new\nline",    VarType::STRING_ ],

	// Object
	[ new stdClass,   VarType::OBJECT_ ],
	[ new stdClass(), VarType::OBJECT_ ],

	// Array
	[ [],             VarType::ARRAY_ ],
	[ [ 1 ],          VarType::ARRAY_ ],
	[ [ 'A' => 'B' ], VarType::ARRAY_ ],

];

// Run tests
test($cases, function ($input) {
	return VarType::of($input);
});
