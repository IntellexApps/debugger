<?php

// Define cases
$line = 44;
$header = sprintf('%-137s│', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'index.php' . ' : ' . $line);
$cases = [
	[ new Exception(), <<<X
╒══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╕
│                                                                                                                                          │
│ Exception                                                                                                                                │
│                                                                                                                                          │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                                                          │
│ $header
│                                                                                                                                          │
│      36                                                                                                                                  │
│      37 // Load and run all tests                                                                                                        │
│      38 \$index = basename(__FILE__);                                                                                                     │
│      39 \$classes = glob('./*Test');                                                                                                      │
│      40 foreach (\$classes as \$class) {                                                                                                   │
│      41   \$files = glob("{\$class}/*.php");                                                                                               │
│      42   foreach (\$files as \$file) {                                                                                                    │
│      43     /** @noinspection PhpIncludeInspection */                                                                                    │
│  --> 44     require \$file;                                                                                                               │
│      45   }                                                                                                                              │
│      46 }                                                                                                                                │
│      47                                                                                                                                  │
│      48 echo 'All tests passed' . PHP_EOL;                                                                                               │
│      49 exit(0);                                                                                                                         │
│      50                                                                                                                                  │
│                                                                                                                                          │
│                                                                                                                                          │
╘══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╛

X
	],
	[ new Exception('Incident test', 501), <<<X
╒══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╕
│                                                                                                                                          │
│ Exception                                                                                                                                │
│                                                                                                                                          │
│ Incident test                                                                                                                            │
│                                                                                                                                          │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                                                          │
│ $header
│                                                                                                                                          │
│      36                                                                                                                                  │
│      37 // Load and run all tests                                                                                                        │
│      38 \$index = basename(__FILE__);                                                                                                     │
│      39 \$classes = glob('./*Test');                                                                                                      │
│      40 foreach (\$classes as \$class) {                                                                                                   │
│      41   \$files = glob("{\$class}/*.php");                                                                                               │
│      42   foreach (\$files as \$file) {                                                                                                    │
│      43     /** @noinspection PhpIncludeInspection */                                                                                    │
│  --> 44     require \$file;                                                                                                               │
│      45   }                                                                                                                              │
│      46 }                                                                                                                                │
│      47                                                                                                                                  │
│      48 echo 'All tests passed' . PHP_EOL;                                                                                               │
│      49 exit(0);                                                                                                                         │
│      50                                                                                                                                  │
│                                                                                                                                          │
│                                                                                                                                          │
╘══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╛

X
	]
];

// Run tests
\Intellex\Debugger\Config::setTemplate('plain');
test($cases, function ($input) {
	ob_start();
	\Intellex\Debugger\IncidentHandler::handleException($input, false);
	return ob_get_clean();
});

// Return to default
\Intellex\Debugger\Config::setTemplate(null);
