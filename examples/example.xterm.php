<?php

require_once '../CLICommander.class.php';

$cli = new CLICommander();

$cli->Clear();

if ($cli->HasXtermSupport()) {
	$cli->WriteLine("Testing xterm system colors");
	
	for ($a = 0; $a < 16; $a++) {
		if ($a == 7 || $a == 15) {
			$cli->WriteLine('  ',255,$a);
		} else {
			$cli->write('  ', 255,$a);
		}
	}
	for ($a = 0; $a < 16; $a++) {
		if ($a == 7 || $a == 15) {
			$cli->WriteLine('##',$a);
		} else {
			$cli->write('##', $a);
		}
	}
	
	$cli->WriteLine();
	$cli->WriteLine("Writing xterm Color Cubes");
	
	for ($a = 16; $a < 232; $a++) {
		if (($a - 15) % 36 == 0) {
			$cli->WriteLine('  ',255,$a);
		} else {
			$cli->Write('  ',255,$a);
		}
	}
	for ($a = 16; $a < 232; $a++) {
		if (($a - 15) % 36 == 0) {
			$cli->WriteLine('##',$a);
		} else {
			$cli->Write('##',$a);
		}
	}
	
	$cli->WriteLine();
	$cli->WriteLine("Writing Greyscale Ramp");
	for ($a = 232; $a < 256; $a++) $cli->Write('  ',255,$a);
	$cli->WriteLine();
	for ($a = 232; $a < 256; $a++) $cli->Write('##',$a);
	
	$cli->WriteLine();
} else {
	$cli->WriteLine("This terminal does not have support for xterm colors");
	$cli->WriteLine('$_SERVER[TERM] = ' . $_SERVER['TERM']);
}

?>