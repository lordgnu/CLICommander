<?php

require_once '../CLICommander.class.php';

$cli = new CLICommander();

print_r($cli->GetArguments());

if ($cli->ArgumentPassed('title')) {
	$cli->SetTerminalTitle($cli->GetArgumentValue('title'));
}

if ($cli->ArgumentPassed('d')) {
	$cli->WriteLine("If I was a real script, I would start as a daemon since you passed -d");
}

if ($cli->ArgumentPassed('verbose') || $cli->ArgumentPassed('v')) {
	$cli->WriteLine("You asked me to be verbose so I will write 5 lines about myself now");
	$cli->WriteLine("My name is CLICommander");
	$cli->WriteLine("My version is ".CLICommander::$version);
	$cli->WriteLine("I was written by Don Bauer");
	$cli->WriteLine("You can follow me on github at https://github.com/lordgnu/CLICommander");
	$cli->WriteLine("I like math!");
}

$cli->WriteLine("The file I am suppose to be processing is " . $cli->GetArgumentValue('file'));

if ($cli->ArgumentPassed('tacos')) {
	$cli->WriteLine("I like tacos!");
}

?>