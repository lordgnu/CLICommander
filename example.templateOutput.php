<?php

require_once 'CLICommander.class.php';

$cli = new CLICommander();

$output = <<<OUTPUT
Hello! {red|white|bold}This is a test{reset}
This is demonstrating templated color output
{blue}This should be blue{reset} And this should be normal
{default|red}This should have a red background with a {green}green{default} word
OUTPUT;

$cli->WriteTemplate($output);
?>