<?php require '../vendor/autoload.php';

\Intellex\Debugger\Config::setContextLines(4);
\Intellex\Debugger\IncidentHandler::register();

function normalize($value, $scale) {
	return $value / $scale;
}

function activate($description, $value, $scale) {
	return $description . ' -> ' . normalize($value, $scale);
}

function start($jobName, $scale) {
	activate('Job Name:' . $jobName, 7261, $scale);
}

start('increment', 0);
