<?php

// Include CLICommander
require_once '../CLICommander.class.php';

// Instance a new CLICommander object
$cli = new CLICommander();

// Create an array of colors to loop through
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

// Create an array of styles to loop through
$styles = array(
	'default',
	'bold',
	'faint',
	'italic',
	'underline',
	'blink',
	'blinkfast',
	'reverse',
	'conceal',
	'doubleunderline',
	'subscript',
	'superscript'
);

// Create a couple style arrays
$style1 = array(
	'foreground'	=>	'white',
	'background'	=>	'red',
	'style'	=>	'bold'
);

$style2 = array(
	'foreground'	=>	'blue',
	'background'	=>	'default',
	'style'	=>	'underline'
);

// Clear the screen
$cli->Clear();

// Change the terminal title
$cli->WriteLine("Changing Terminal Title");
$cli->WriteLine("-----------------------");
for ($i = 3; $i > 0; $i--) {
	$cli->WriteLine("Changing title in {$i}...");
	sleep(1);
}
$cli->SetTerminalTitle("CLICommander ANSI Example");
sleep(1);
$cli->WriteLine("Terminal title should now be: CLICommander ANSI Example");

// Write the ANSI colors
$cli->WriteLine();
$cli->WriteLine("Initiating Color Test");
$cli->WriteLine("---------------------");
foreach ($colors as $fg) {
	foreach ($colors as $bg) {
		$cli->Write(" TEST ", $fg, $bg);
	}
	$cli->WriteLine();
}

// Write with ANSI styles
$cli->WriteLine();
$cli->WriteLine("Initiating Styles Test");
$cli->WriteLine("-----------------------");
foreach ($styles as $style) {
	$cli->SetDefaultStyle($style);
	$cli->WriteLine("This is a test of style: {$style}");
}
$cli->SetDefaultStyle('default');

// Ding the terminal bell a few times
$cli->WriteLine();
$cli->WriteLine("Initiating Bell Test");
$cli->WriteLine("--------------------");

for ($i = 0; $i < 5; $i++) {
	$cli->Bell();
	$cli->WriteLine("Ding!");
	sleep(1);
}

// Write with the style arrays
$cli->WriteLine();
$cli->WriteLine("Testing writing with style array");
$cli->WriteLine("--------------------------------");
$cli->WriteLine("This is a test of style1",$style1);
$cli->WriteLine("This is a test of style2",$style2);
?>