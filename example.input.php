<?php 

require_once 'CLICommander.class.php';

$cli = new CLICommander();

$cli->Clear();
$cli->WriteLine("User Input Test");

$firstName = $cli->Prompt("What is your first name?");
$lastName = $cli->Prompt("What is your last name?");

$cli->WriteLine("Hello {$firstName} {$lastName}!");

$password = $cli->MaskedPrompt("What is your password?",'red');
$cli->WriteLine("Your password is: {$password}");

$cli->WriteLine("Press any key to exit");

$a = $cli->GetChar(true);
$cli->WriteLine("You pressed '{$a}' ASCII:" . ord($a));
$cli->WriteLine("Goodbye");

?>