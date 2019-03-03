<?php

// Define cases
$cases = [
	[
		[ '123' ], <<<EXPECT
<div class="intellex-debugger-code"><div class="line"><span class="php-line">1</span>&nbsp;<span class="php-html">
123</span>
</div></div>
EXPECT
	],
	[
		[ "Line 0\nLine 1" ], <<<EXPECT
<div class="intellex-debugger-code"><div class="line"><span class="php-line">1</span>&nbsp;<span class="php-html">
Line&nbsp;0&nbsp;</div>
<div class="line"><span class="php-line">2</span>&nbsp;Line&nbsp;1</span>
</div></div>
EXPECT
	],
	[
		[ "Line 0\nLine 1\nLine 2", 2 ], <<<EXPECT
<div class="intellex-debugger-code"><div class="line"><span class="php-line">1</span>&nbsp;<span class="php-html">
Line&nbsp;0&nbsp;</div>
<div class="highlighted line"><span class="php-line">2</span>&nbsp;Line&nbsp;1&nbsp;</div>
<div class="line"><span class="php-line">3</span>&nbsp;Line&nbsp;2</span>
</div></div>
EXPECT
	],
	[
		[ "Line 0\nLine 1\nLine 2", 2, 1 ], <<<EXPECT
<div class="intellex-debugger-code"><div class="highlighted line"><span class="php-line">2</span>&nbsp;Line&nbsp;1&nbsp;</div>
<div class="line"><span class="php-line">3</span>&nbsp;Line&nbsp;2</span>
</div></div>
EXPECT
	],
	[
		[ "Line 0\nLine 1\nLine 2\nLine 3\nLine 4\nLine 5\nLine 6\nLine 7", 4, 3, 6 ], <<<EXPECT
<div class="intellex-debugger-code"><div class="highlighted line"><span class="php-line">4</span>&nbsp;Line&nbsp;3&nbsp;</div>
<div class="line"><span class="php-line">5</span>&nbsp;Line&nbsp;4&nbsp;</div>
<div class="line"><span class="php-line">6</span>&nbsp;Line&nbsp;5&nbsp;</div>
<div class="line"><span class="php-line">7</span>&nbsp;Line&nbsp;6&nbsp;</div></div>
EXPECT
	],
];

// Run tests
test($cases, function ($input) {
	return forward_static_call_array('\Intellex\Debugger\Helper::formatCodeAsHTML', $input);
});
