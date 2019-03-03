<?php

// Define cases
$line = substr_count(file_get_contents(__FILE__), "\n") - 5;
$file = str_replace('/', '\\/', __FILE__);
$cases = [
	[ null, <<<X
{
    "file": "{$file}",
    "line": {$line},
    "value": null
}
X
	],
	[ 'string', <<<X
{
    "file": "{$file}",
    "line": {$line},
    "value": "string"
}
X
	],
	[ ' ! ', <<<X
{
    "file": "{$file}",
    "line": {$line},
    "value": " ! "
}
X
	],
	[ [], <<<X
{
    "file": "{$file}",
    "line": {$line},
    "value": []
}
X
	],
	[ [ 'no', 'yes', 'other' => [ 'maybe' => 'Still thinking about it', 'undisclosed' => 'Refuse to answer' ] ], <<<X
{
    "file": "{$file}",
    "line": {$line},
    "value": {
        "0": "no",
        "1": "yes",
        "other": {
            "maybe": "Still thinking about it",
            "undisclosed": "Refuse to answer"
        }
    }
}
X
	],
	[ new stdClass(), <<<X
{
    "file": "{$file}",
    "line": {$line},
    "value": {}
}
X
	],
	[ new \Intellex\Debugger\TraceStep([]), <<<X
{
    "file": "{$file}",
    "line": {$line},
    "value": {
        "function": null,
        "args": null,
        "file": null,
        "line": null,
        "snippet": []
    }
}
X
	]
];

// Run tests
\Intellex\Debugger\Config::setTemplate('json');
test($cases, function ($input) {
	ob_start();
	\Intellex\Debugger\VarDump::from($input);
	return ob_get_clean();
});

// Return to default
\Intellex\Debugger\Config::setTemplate(null);
