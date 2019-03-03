<?php

/**
 * @var \Intellex\Debugger\Trace $trace The Trace to show to the user.
 */

// Decide which file snippet to show as opened by default
$open = 1;
foreach ($trace->getSteps() as $i => $step) {
	if ($step->getFile() && $step->getLine() && (empty($trace->getOrigin()) || ($trace->getOrigin()->getFile() == $step->getFile() && $trace->getOrigin()->getLine() == $step->getLine()))) {
		$open = $i;
		break;
	}
}

// Output as HTML
if (!\Intellex\Debugger\Helper::isCli() && !headers_sent()) {
	header('Content-Type: text/html');
}

// The list of additional global variables
$globals = [ 'Get' => $_GET, 'Post' => $_POST, 'Server' => $_SERVER, 'Cookie' => $_COOKIE, 'Files' => $_FILES ];

?>
<!DOCTYPE html>
<html lang="en_US">
<head>
	<meta charset="utf-8">
	<title>Exception: <?php echo $trace->getThrowable()->getMessage() ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style>
		* { box-sizing: border-box; }
		html { min-height: 100%; }
		body { padding: 0 0 42px 0; margin: 0; font: normal 16px Arial; background: white; width: 100%; min-height: 100%; color: #444; }

		/* Header */
		header { padding: 24px 24px 0 24px; background: #C05C5C; color: white; top: 0; left: 0; right: 0; position: relative; }
		header h1 { font-size: 24px; margin: 0 0 24px 0; }
		header h2 { font-size: 32px; margin: 24px 0; }
		header svg.icon { width: 110px; height: 110px; fill: white; float: right; }
		header nav { position; absolute; left: 0; bottom: 0; }
		header nav a { display: inline-block; padding: 12px 24px; border: solid 1px transparent; text-decoration: none !important; color: white; font-size: 18px; }
		header nav a:hover { background: white; color: #555; }
		header nav a.active { color: #555; background: white; border-color: #bbb; border-bottom: solid 1px white; }

		/* Sections */
		section { display: none; }
		section.active { display: block; }

		/* Stack trace list */
		ul.trace { list-style: none; padding: 0; margin: 0; }
		ul.trace > li { border-bottom: 1px solid #BBB; }
		ul.trace > li .header { font-size: 22px; text-decoration: none !important; width: 100%; padding: 12px 24px; color: inherit; display: block; }
		ul.trace > li .header[href]:hover { background-color: #EEE; }
		ul.trace > li .header:nth-child(0) { display: none; }

		/* Stack trace header */
		.class { color: #3f98cf; }
		.type { color: #3f98cf; }
		.function { color: #3f98cf; }
		.arg { color: #888; }
		.path { color: #888; margin-top: 6px; padding-left: 24px; }

		/* Stack trace code snippet */
		pre.global { padding: 24px; margin: 0; }
		div.code { display: none; padding: 12px 48px 12px; }
		div.code.opened { display: block; }
		div.intellex-debugger-code { font: normal 16px Monospace, Courier; background: none; color: #555; }
		div.intellex-debugger-code .line:hover { background: #EEE; }
		div.intellex-debugger-code .line.highlighted { background: #FFFF92; }
		div.intellex-debugger-code div.array { display: none; margin: 0 10px;; }
		div.intellex-debugger-code div.array.opened { display: block; }
		div.intellex-debugger-code span.php-line { color: #BBB; }
		div.intellex-debugger-code span.php-comment { color: #BBB; }
		div.intellex-debugger-code span.php-default { }
		div.intellex-debugger-code span.php-html { }
		div.intellex-debugger-code span.php-keyword { color: #1967E1; }
		div.intellex-debugger-code span.php-string { color: #129712; }

		/* Footer */
		footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 12px; text-align: center; background: #C05C5C; color: white; width: 100%; }
		footer a { color: inherit; text-decoration: none; }
		footer a:hover { text-decoration: underline; }
		footer a svg { fill: white; width: 16px; height: 16px; margin: 0 6px -2px; }
	</style>
</head>
<body>
<header>
	<svg class="icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
		<path d="M1024 1375v-190q0-14-9.5-23.5t-22.5-9.5h-192q-13 0-22.5 9.5t-9.5 23.5v190q0 14 9.5 23.5t22.5 9.5h192q13 0 22.5-9.5t9.5-23.5zm-2-374l18-459q0-12-10-19-13-11-24-11h-220q-11 0-24 11-10 7-10 21l17 457q0 10 10 16.5t24 6.5h185q14 0 23.5-6.5t10.5-16.5zm-14-934l768 1408q35 63-2 126-17 29-46.5 46t-63.5 17h-1536q-34 0-63.5-17t-46.5-46q-37-63-2-126l768-1408q17-31 47-49t65-18 65 18 47 49z"></path>
	</svg>

	<h1><?php echo get_class($trace->getThrowable()) ?></h1>
	<h2><?php echo $trace->getThrowable()->getMessage() ?></h2>

	<nav>
		<a href="#StackTrace" onclick="setNavigationTab(this); return false;" class="active">Stack trace</a>
		<?php foreach ($globals as $code => $var) { ?>
			<a href="#<?= $code ?>" onclick="setNavigationTab(this); return false;">$_<?php echo strtoupper($code) ?></a>
		<?php } ?>
	</nav>
</header>

<section id="StackTrace" class="active">
	<ul class="trace">
		<?php foreach ($trace->getSteps() as $i => $step) { ?>
			<li class="step">
				<a class="header" data-step="<?php echo $i ?>"<?php echo $step->getFile() ? ' onclick="toggleCodeSnippet(this); return false;" title="Toggle code snippet" href="#step-' . $i . '"' : null ?>>

					<?php if ($step->getClass()) { ?>
						<span class="class"><?php echo $step->getClass() ?></span>
					<?php } ?>

					<?php if ($step->getType()) { ?>
						<span class="type"><?php echo $step->getType() ?></span>
					<?php } ?>

					<?php if ($step->getFunction()) { ?>
						<span class="function"><?php echo $step->getFunction() ?></span>
						(<?php if ($step->getArgs())
							foreach ($step->getArgs() as $a => $arg)
								echo ($a ? ', ' : null) . '<span class="arg">' . \Intellex\Debugger\Helper::getReadableValue($arg, 256) . '</span>' ?>)
					<?php } ?>

					<div class="path"><?php echo $step->getFile() ? $step->getFile() . ($step->getLine() ? ' : ' . $step->getLine() : null) : null ?></div>

				</a>

				<div class="code<?php echo $open === $i ? ' opened' : null ?>" data-step="<?php echo $i ?>">
					<?php echo \Intellex\Debugger\Helper::formatCodeAsHTML(file_get_contents($step->getFile()), $step->getLine(), $step->getLine() - \Intellex\Debugger\Config::getContextLines(), $step->getLine() + \Intellex\Debugger\Config::getContextLines()) ?>
				</div>
			</li>
		<?php } ?>
	</ul>
</section>

<?php foreach ($globals as $code => $data) { ?>
	<section id="<?php echo $code ?>">
		<pre class="global"><?php echo \Intellex\Debugger\Helper::getReadableValue($data) ?></pre>
	</section>
<?php } ?>

<footer>
	<a href="https://github.com/IntellexApps/debugger" target="_blank" title="Show on GitHub">
		<svg width="16" height="16" viewBox="0 0 438 438" xmlns="http://www.w3.org/2000/svg">
			<path d="M409.132,114.573c-19.608-33.596-46.205-60.194-79.798-79.8C295.736,15.166,259.057,5.365,219.271,5.365 c-39.781,0-76.472,9.804-110.063,29.408c-33.596,19.605-60.192,46.204-79.8,79.8C9.803,148.168,0,184.854,0,224.63 c0,47.78,13.94,90.745,41.827,128.906c27.884,38.164,63.906,64.572,108.063,79.227c5.14,0.954,8.945,0.283,11.419-1.996 c2.475-2.282,3.711-5.14,3.711-8.562c0-0.571-0.049-5.708-0.144-15.417c-0.098-9.709-0.144-18.179-0.144-25.406l-6.567,1.136 c-4.187,0.767-9.469,1.092-15.846,1c-6.374-0.089-12.991-0.757-19.842-1.999c-6.854-1.231-13.229-4.086-19.13-8.559 c-5.898-4.473-10.085-10.328-12.56-17.556l-2.855-6.57c-1.903-4.374-4.899-9.233-8.992-14.559 c-4.093-5.331-8.232-8.945-12.419-10.848l-1.999-1.431c-1.332-0.951-2.568-2.098-3.711-3.429c-1.142-1.331-1.997-2.663-2.568-3.997 c-0.572-1.335-0.098-2.43,1.427-3.289c1.525-0.859,4.281-1.276,8.28-1.276l5.708,0.853c3.807,0.763,8.516,3.042,14.133,6.851 c5.614,3.806,10.229,8.754,13.846,14.842c4.38,7.806,9.657,13.754,15.846,17.847c6.184,4.093,12.419,6.136,18.699,6.136 c6.28,0,11.704-0.476,16.274-1.423c4.565-0.952,8.848-2.383,12.847-4.285c1.713-12.758,6.377-22.559,13.988-29.41 c-10.848-1.14-20.601-2.857-29.264-5.14c-8.658-2.286-17.605-5.996-26.835-11.14c-9.235-5.137-16.896-11.516-22.985-19.126 c-6.09-7.614-11.088-17.61-14.987-29.979c-3.901-12.374-5.852-26.648-5.852-42.826c0-23.035,7.52-42.637,22.557-58.817 c-7.044-17.318-6.379-36.732,1.997-58.24c5.52-1.715,13.706-0.428,24.554,3.853c10.85,4.283,18.794,7.952,23.84,10.994 c5.046,3.041,9.089,5.618,12.135,7.708c17.705-4.947,35.976-7.421,54.818-7.421s37.117,2.474,54.823,7.421l10.849-6.849 c7.419-4.57,16.18-8.758,26.262-12.565c10.088-3.805,17.802-4.853,23.134-3.138c8.562,21.509,9.325,40.922,2.279,58.24 c15.036,16.18,22.559,35.787,22.559,58.817c0,16.178-1.958,30.497-5.853,42.966c-3.9,12.471-8.941,22.457-15.125,29.979 c-6.191,7.521-13.901,13.85-23.131,18.986c-9.232,5.14-18.182,8.85-26.84,11.136c-8.662,2.286-18.415,4.004-29.263,5.146 c9.894,8.562,14.842,22.077,14.842,40.539v60.237c0,3.422,1.19,6.279,3.572,8.562c2.379,2.279,6.136,2.95,11.276,1.995 c44.163-14.653,80.185-41.062,108.068-79.226c27.88-38.161,41.825-81.126,41.825-128.906 C438.536,184.851,428.728,148.168,409.132,114.573z"></path>
		</svg>
		intellexapps/debugger
	</a>
</footer>

<script type="text/javascript">

	function toggleCodeSnippet(item) {
		item.nextSibling.nextSibling.classList.toggle('opened');
	}

	function setNavigationTab(item) {
		closeSiblings(item, 'active');

		var section = document.getElementById(item.href.replace(/^.+#/, ''));
		closeSiblings(section, 'active');
		return false;
	}

	function closeSiblings(item, className) {
		for (var i in item.parentNode.childNodes) {
			if (item.parentNode.childNodes.hasOwnProperty(i)) {
				if (item.parentNode.childNodes[i].nodeName && item.parentNode.childNodes[i].nodeName !== '#text') {
					item.parentNode.childNodes[i].classList.remove(className);
				}
			}
		}
		item.classList.add(className);
	}
</script>
</body>
</html>
