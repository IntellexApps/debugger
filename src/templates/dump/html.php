<?php
/**
 * @var mixed $data          The show to debug.
 * @var bool  $useDebugPrint True to use the __debug() method of the class, if available.
 */
?>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color: #232323!important; background: #FC9C63!important; padding: 16px!important; margin-bottom: 8px!important; font: 16px Monospace!important; clear: both!important; border: solid 3px black!important;">
	<span><strong><?= $data['file'] ?></strong> (line <strong><?= $data['line'] ?></strong>)</span>
	<pre style="white-space: pre-wrap!important; word-break: break-all!important; overflow-wrap: break-word!important;"><?= htmlentities(\Intellex\Debugger\Helper::getReadableValue($data['value'], $useDebugPrint)) ?></pre>
</div>
