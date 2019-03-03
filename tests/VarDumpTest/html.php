<?php

// Define cases
$line = substr_count(file_get_contents(__FILE__), "\n") - 5;
$file = __FILE__;
$cases = [
	[ null, <<<X
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong>{$file}</strong> (line <strong>{$line}</strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;">(null value)</pre>
</div>

X
	],
	[ 'string', <<<X
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong>{$file}</strong> (line <strong>{$line}</strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;">string</pre>
</div>

X
	],
	[ ' ! ', <<<X
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong>{$file}</strong> (line <strong>{$line}</strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;">string(3): &quot; ! &quot;</pre>
</div>

X
	],
	[ [], <<<X
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong>{$file}</strong> (line <strong>{$line}</strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;">Array
(
)
</pre>
</div>

X
	],
	[ [ 'no', 'yes', 'other' => [ 'maybe' => 'Still thinking about it', 'undisclosed' => 'Refuse to answer' ] ], <<<X
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong>{$file}</strong> (line <strong>{$line}</strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;">Array
(
    [0] =&gt; no
    [1] =&gt; yes
    [other] =&gt; Array
        (
            [maybe] =&gt; Still thinking about it
            [undisclosed] =&gt; Refuse to answer
        )

)
</pre>
</div>

X
	],
	[ new stdClass(), <<<X
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong>{$file}</strong> (line <strong>{$line}</strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;">stdClass Object
(
)
</pre>
</div>

X
	],
	[ new \Intellex\Debugger\TraceStep([]), <<<X
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong>{$file}</strong> (line <strong>{$line}</strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;">Intellex\Debugger\TraceStep Object
(
    [type:Intellex\Debugger\TraceStep:private] =&gt; 
    [object:Intellex\Debugger\TraceStep:private] =&gt; 
    [class:Intellex\Debugger\TraceStep:private] =&gt; 
    [function:Intellex\Debugger\TraceStep:private] =&gt; 
    [args:Intellex\Debugger\TraceStep:private] =&gt; 
    [file:Intellex\Debugger\TraceStep:private] =&gt; 
    [line:Intellex\Debugger\TraceStep:private] =&gt; 
    [snippet:Intellex\Debugger\TraceStep:private] =&gt; Array
        (
        )

)
</pre>
</div>

X
	]
];

// Run tests
\Intellex\Debugger\Config::setTemplate('html');
test($cases, function ($input) {
	ob_start();
	\Intellex\Debugger\VarDump::from($input);
	return ob_get_clean();
});

// Return to default
\Intellex\Debugger\Config::setTemplate(null);
