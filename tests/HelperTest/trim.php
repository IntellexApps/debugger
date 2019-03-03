<?php

// Define cases
$cases = [
	[ [ '', 0 ], '' ],
	[ [ '', 5 ], '' ],
	[ [ '12345', 5 ], '12345' ],
	[ [ 'exact', 5 ], 'exact' ],
	[ [ '012345', 5 ], '0123…' ],
	[ [ 'More than five', 5 ], 'More…' ],
	[ [ 'Custom ellipsis', 10, '?' ], 'Custom el?' ],
	[ [ '1234567890', 10, '~~~' ], '1234567890' ],
	[ [ '1234567890!', 10, '~~~' ], '1234567~~~' ],
];

// Run tests
test($cases, function ($input) {
	return forward_static_call_array('\Intellex\Debugger\Helper::trim', $input);
});
