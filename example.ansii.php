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

$cli->Clear();
$cli->WriteLine("Changing Terminal Title");
$cli->WriteLine("-----------------------");
for ($i = 3; $i > 0; $i--) {
	$cli->WriteLine("Changing title in {$i}...");
	sleep(1);
}
$cli->SetTerminalTitle("CLICommander ANSI Example");
sleep(1);
$cli->WriteLine("Terminal title should now be: CLICommander ANSI Example");

$cli->WriteLine();
$cli->WriteLine("Initiating Color Test");
$cli->WriteLine("---------------------");
foreach ($colors as $fg) {
	foreach ($colors as $bg) {
		$cli->Write(" TEST ", $fg, $bg);
	}
	$cli->WriteLine();
}

$cli->WriteLine();
$cli->WriteLine("Initiating Styles Test");
$cli->WriteLine("-----------------------");
foreach ($styles as $style) {
	$cli->SetDefaultStyle($style);
	$cli->WriteLine("This is a test of style: {$style}");
}
$cli->SetDefaultStyle('default');

$cli->WriteLine();
$cli->WriteLine("Initiating Bell Test");
$cli->WriteLine("--------------------");

for ($i = 0; $i < 5; $i++) {
	$cli->Bell();
	$cli->WriteLine("Ding!");
	sleep(1);
}

$cli->WriteLine();
$cli->WriteLine("Testing writing with style array");
$cli->WriteLine("--------------------------------");
$cli->WriteLine("This is a test of style1",$style1);
$cli->WriteLine("This is a test of style2",$style2);
?>