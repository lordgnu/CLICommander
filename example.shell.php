<?php

require_once 'CLICommander.class.php';

$cli = new CLICommander();

$colors = array(
	'black',
	'red',
	'green',
	'yellow',
	'blue',
	'magenta',
	'cyan',
	'white'
);

while (true) {
	$cmd = $cli->Prompt("Shell>",'blue');
	
	switch (strtolower($cmd)) {
		case 'help':
			$cli->WriteLine("Available commands:");
			$cli->WriteLine(" * help    Prints this help");
			$cli->WriteLine(" * exit    Exits this shell");
			$cli->WriteLine(" * who     Simple input test");
			$cli->WriteLine(" * clear   Clears the terminal");
			$cli->WriteLine(" * bell    Dings the bell");
			$cli->writeLine(" * colors  Change Terminal Colors");
			break;
		case 'exit':
			$cli->WriteLine("Goodbye!");
			exit(0);
		case 'clear':
			$cli->Clear();
			break;
		case 'bell':
			$cli->Bell();
			break;
		case 'who':
			$name = $cli->Prompt("What is your name?");
			$cli->WriteLine("Hello {$name}!  Welcome to CLICommander!");
			break;
		case 'colors':
			$cli->WriteLine("Available ANSI colors are: " . implode(' ', $colors));
			
			$fg = $cli->Prompt("What foreground color would you like?");
			$bg = $cli->Prompt("What background color would you like?");
			
			$cli->SetDefaultForegroundColor($fg);
			$cli->SetDefaultBackgroundColor($bg);
			break;
		default:
			if ($cmd != '') $cli->WriteLine("The command '{$cmd}' is not supported",'red');
	}
}

?>