<?php

require_once 'CLICommander.class.php';

$cli = new CLICommander();

$cli->Clear();

$output = <<<OUTPUT
Welcome to the template example!
You can prepare a large amount of text and simply use CLICommander tags to 
style the text in the template inline.

To do this, simply use the following syntax: {{{red}FG_COLOR{reset}|{default|red}BG_COLOR{reset}|{default|default|bold}STYLE{reset}}}

To display the curly brackets instead of attempting to use the tag, simply do two of them in a row {{

It is important to note that color changes are persistant.

So if I change the {|red}background to red then change the {cyan}foreground to cyan, you'll notice that the background is still red{reset}
To reset the colors in a template, simply put a reset tag where you would like to change everything back to default.

You can leave options you don't wish to change blank.  Please see the examples below:

{blue}Just the foreground to blue{reset}
{|blue}Just the background to blue{reset}
{||bold}Just the style to bold{reset}

You can also use {9787AF}Xterm{reset} colors in the same fashion

OUTPUT;

$cli->WriteTemplate($output);
?>