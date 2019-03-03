<?php

// Define cases
$testFile = __DIR__ . DIRECTORY_SEPARATOR . 'testFileContent';
$cases = [
	[
		[ null, [
		] ],
		[
			'type'     => null,
			'object'   => null,
			'class'    => null,
			'function' => null,
			'args'     => null,
			'file'     => null,
			'line'     => null,
			'snippet'  => [],
		]
	],
	[
		[ null, [
			'type'     => '::',
			'object'   => 'This',
			'class'    => 'Self',
			'function' => 'method',
			'args'     => [],
			'file'     => 'bad file',
			'line'     => 2,
			'snippet'  => []
		] ],
		[
			'type'     => '::',
			'object'   => 'This',
			'class'    => 'Self',
			'function' => 'method',
			'args'     => [],
			'file'     => 'bad file',
			'line'     => 2,
			'snippet'  => []
		]
	],
	[
		[ 0, [
			'args' => [],
			'file' => $testFile,
			'line' => 4,
		] ],
		[
			'type'     => null,
			'object'   => null,
			'class'    => null,
			'function' => null,
			'args'     => null,
			'file'     => $testFile,
			'line'     => 4,
			'snippet'  => [
				1  => 'Line 1',
				2  => 'Line 2',
				3  => 'Line 3',
				4  => 'Line 4',
				5  => 'Line 5',
				6  => 'Line 6',
				7  => 'Line 7',
				8  => 'Line 8',
				9  => 'Line 9',
				10 => 'Line 10',
				11 => 'Line 11',
				12 => 'Line 12',
			]
		],
	],
	[
		[ 2, [
			'file'     => $testFile,
			'function' => 'isHere',
			'args'     => [ 100, -1 ],
			'line'     => 4,
		] ],
		[
			'type'     => null,
			'object'   => null,
			'class'    => null,
			'function' => 'isHere',
			'args'     => [ 100, -1 ],
			'file'     => $testFile,
			'line'     => 4,
			'snippet'  => [
				2 => 'Line 2',
				3 => 'Line 3',
				4 => 'Line 4',
				5 => 'Line 5',
				6 => 'Line 6',
			]
		],
	],
	[
		[ 3, [
			'type' => '::',
			'object'   => 'This',
			'function' => 'isSparta',
			'args' => [],
			'file' => $testFile,
			'line' => 4,
		] ],
		[
			'type'     => '::',
			'object'   => 'This',
			'class'    => null,
			'function' => 'isSparta',
			'args'     => [],
			'file'     => $testFile,
			'line'     => 4,
			'snippet'  => [
				1 => 'Line 1',
				2 => 'Line 2',
				3 => 'Line 3',
				4 => 'Line 4',
				5 => 'Line 5',
				6 => 'Line 6',
				7 => 'Line 7',
			]
		],
	]
];

// Run tests
test($cases, function ($input) {
	$step = new \Intellex\Debugger\TraceStep($input[1], $input[0]);
	return [
		'type'     => $step->getType(),
		'object'   => $step->getObject(),
		'class'    => $step->getClass(),
		'function' => $step->getFunction(),
		'args'     => $step->getArgs(),
		'file'     => $step->getFile(),
		'line'     => $step->getLine(),
		'snippet'  => $step->getSnippet(),
	];
});
