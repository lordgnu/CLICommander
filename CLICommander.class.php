<?php

/**
 * @package CLICommander
 * 
 * CLICommander is a set of advanced tools for working with PHP scripts on 
 * the linux/unix command line.  It offers features such as colored output, 
 * options handling, and a whole lot more.  CLICommander implements most 
 * ANSI escape sequences and offers support for basic 16-color, and xterm
 * 256-color output.
 * 
 * If you like CLICommander, please consider donating
 *  - BTC: 1K2tvdYzdDDd8w6vNHQgvbNQnhcHqLEadx
 *  - LTC: LfceD3QH2n1FqH8inqHdKxjBFV55QvuESv
 * 
 * @author Don Bauer <lordgnu@me.com>
 * @link https://github.com/lordgnu/CLICommander
 * @license MIT License
 * 
 * Copyright (c) 2011 Don Bauer <lordgnu@me.com>
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class CLICommander {
	protected $inputSocket;
	protected $outputSocket;
	protected $errorSocket;
	
	private	$usingWindows	=	false;
	private $xtermSupport	=	false;
	private $bashSupport	=	false;
	private $autoReset		=	true;
	
	private $nl	=	PHP_EOL;
	
	private $escape 			=	"\033[%sm";
	private $bell 				=	"\007";
	private $cls 				=	"\033[2J";
	
	private $getBuffer;
	
	private $defaults = array(
		'foreground'	=>	'default',
		'background'	=>	'default',
		'style'			=>	'default'
	);
	
	protected $foregroundColors = array(
		'default'	=>	39,
		'black'		=>	30,
		'red'		=>	31,
		'green'		=>	32,
		'yellow'	=>	33,
		'blue'		=>	34,
		'magenta'	=>	35,
		'cyan'		=>	36,
		'white'		=>	37
	);

	protected $backgroundColors = array(
		'default'	=>	49,
		'black'		=>	40,
		'red'		=>	41,
		'green'		=>	42,
		'yellow'	=>	43,
		'blue'		=>	44,
		'magenta'	=>	45,
		'cyan'		=>	46,
		'white'		=>	47
	);

	protected $styles = array(
		'default'			=>	0,
		'bold'				=>	1,
		'faint'				=>	2,
		'italic'			=>	3,
		'underline'			=>	4,
		'blink'				=>	5,	// Not widely supported
		'blinkfast'			=>	6,	// Not widely supported
		'reverse'			=>	7,
		'conceal'			=>	8,	// Not widely supported
		'doubleunderline'	=>	21,	// Not widely supported
		'subscript'			=>	48,	// Not widely supported
		'superscript'		=>	49	// Not widely supported
	);
	
	protected $xtermColors = array(
		'000000'=>16,'00005F'=>17,'000087'=>18,'0000AF'=>19,'0000D7'=>20,
		'0000FF'=>21,'005F00'=>22,'005F5F'=>23,'005F87'=>24,'005FAF'=>25,
		'005FD7'=>26,'005FFF'=>27,'008700'=>28,'00875F'=>29,'008787'=>30,
		'0087AF'=>31,'0087D7'=>32,'0087FF'=>33,'00AF00'=>34,'00AF5F'=>35,
		'00AF87'=>36,'00AFAF'=>37,'00AFD7'=>38,'00AFFF'=>39,'00D700'=>40,
		'00D75F'=>41,'00D787'=>42,'00D7AF'=>43,'00D7D7'=>44,'00D7FF'=>45,
		'00FF00'=>46,'00FF5F'=>47,'00FF87'=>48,'00FFAF'=>49,'00FFD7'=>50,
		'00FFFF'=>51,'5F0000'=>52,'5F005F'=>53,'5F0087'=>54,'5F00AF'=>55,
		'5F00D7'=>56,'5F00FF'=>57,'5F5F00'=>58,'5F5F5F'=>59,'5F5F87'=>60,
		'5F5FAF'=>61,'5F5FD7'=>62,'5F5FFF'=>63,'5F8700'=>64,'5F875F'=>65,
		'5F8787'=>66,'5F87AF'=>67,'5F87D7'=>68,'5F87FF'=>69,'5FAF00'=>70,
		'5FAF5F'=>71,'5FAF87'=>72,'5FAFAF'=>73,'5FAFD7'=>74,'5FAFFF'=>75,
		'5FD700'=>76,'5FD75F'=>77,'5FD787'=>78,'5FD7AF'=>79,'5FD7D7'=>80,
		'5FD7FF'=>81,'5FFF00'=>82,'5FFF5F'=>83,'5FFF87'=>84,'5FFFAF'=>85,
		'5FFFD7'=>86,'5FFFFF'=>87,'870000'=>88,'87005F'=>89,'870087'=>90,
		'8700AF'=>91,'8700D7'=>92,'8700FF'=>93,'875F00'=>94,'875F5F'=>95,
		'875F87'=>96,'875FAF'=>97,'875FD7'=>98,'875FFF'=>99,'878700'=>100,
		'87875F'=>101,'878787'=>102,'8787AF'=>103,'8787D7'=>104,'8787FF'=>105,
		'87AF00'=>106,'87AF5F'=>107,'87AF87'=>108,'87AFAF'=>109,'87AFD7'=>110,
		'87AFFF'=>111,'87D700'=>112,'87D75F'=>113,'87D787'=>114,'87D7AF'=>115,
		'87D7D7'=>116,'87D7FF'=>117,'87FF00'=>118,'87FF5F'=>119,'87FF87'=>120,
		'87FFAF'=>121,'87FFD7'=>122,'87FFFF'=>123,'AF0000'=>124,'AF005F'=>125,
		'AF0087'=>126,'AF00AF'=>127,'AF00D7'=>128,'AF00FF'=>129,'AF5F00'=>130,
		'AF5F5F'=>131,'AF5F87'=>132,'AF5FAF'=>133,'AF5FD7'=>134,'AF5FFF'=>135,
		'AF8700'=>136,'AF875F'=>137,'AF8787'=>138,'AF87AF'=>139,'AF87D7'=>140,
		'AF87FF'=>141,'AFAF00'=>142,'AFAF5F'=>143,'AFAF87'=>144,'AFAFAF'=>145,
		'AFAFD7'=>146,'AFAFFF'=>147,'AFD700'=>148,'AFD75F'=>149,'AFD787'=>150,
		'AFD7AF'=>151,'AFD7D7'=>152,'AFD7FF'=>153,'AFFF00'=>154,'AFFF5F'=>155,
		'AFFF87'=>156,'AFFFAF'=>157,'AFFFD7'=>158,'AFFFFF'=>159,'D70000'=>160,
		'D7005F'=>161,'D70087'=>162,'D700AF'=>163,'D700D7'=>164,'D700FF'=>165,
		'D75F00'=>166,'D75F5F'=>167,'D75F87'=>168,'D75FAF'=>169,'D75FD7'=>170,
		'D75FFF'=>171,'D78700'=>172,'D7875F'=>173,'D78787'=>174,'D787AF'=>175,
		'D787D7'=>176,'D787FF'=>177,'D7AF00'=>178,'D7AF5F'=>179,'D7AF87'=>180,
		'D7AFAF'=>181,'D7AFD7'=>182,'D7AFFF'=>183,'D7D700'=>184,'D7D75F'=>185,
		'D7D787'=>186,'D7D7AF'=>187,'D7D7D7'=>188,'D7D7FF'=>189,'D7FF00'=>190,
		'D7FF5F'=>191,'D7FF87'=>192,'D7FFAF'=>193,'D7FFD7'=>194,'D7FFFF'=>195,
		'FF0000'=>196,'FF005F'=>197,'FF0087'=>198,'FF00AF'=>199,'FF00D7'=>200,
		'FF00FF'=>201,'FF5F00'=>202,'FF5F5F'=>203,'FF5F87'=>204,'FF5FAF'=>205,
		'FF5FD7'=>206,'FF5FFF'=>207,'FF8700'=>208,'FF875F'=>209,'FF8787'=>210,
		'FF87AF'=>211,'FF87D7'=>212,'FF87FF'=>213,'FFAF00'=>214,'FFAF5F'=>215,
		'FFAF87'=>216,'FFAFAF'=>217,'FFAFD7'=>218,'FFAFFF'=>219,'FFD700'=>220,
		'FFD75F'=>221,'FFD787'=>222,'FFD7AF'=>223,'FFD7D7'=>224,'FFD7FF'=>225,
		'FFFF00'=>226,'FFFF5F'=>227,'FFFF87'=>228,'FFFFAF'=>229,'FFFFD7'=>230,
		'FFFFFF'=>231,'080808'=>232,'121212'=>233,'1C1C1C'=>234,'262626'=>235,
		'303030'=>236,'3A3A3A'=>237,'444444'=>238,'4E4E4E'=>239,'585858'=>240,
		'626262'=>241,'6C6C6C'=>242,'767676'=>243,'808080'=>244,'8A8A8A'=>245,
		'949494'=>246,'9E9E9E'=>247,'A8A8A8'=>248,'B2B2B2'=>249,'BCBCBC'=>250,
		'C6C6C6'=>251,'D0D0D0'=>252,'DADADA'=>253,'E4E4E4'=>254,'EEEEEE'=>255
	);
	
	private $argv;
	private $argc;
	
	public function CLICommander($outputStream = 'php://stdout', $inputStream = 'php://stdin', $errorStream = 'php://stderr') {
		// Check to see if we are using windows
		if (substr(php_uname('s'),0,7) == 'Windows') $this->usingWindows = true;
		
		// Open our sockets
		$this->outputSocket = @fopen($outputStream, 'w');
		$this->inputSocket = @fopen($inputStream, 'r');
		$this->errorSocket = @fopen($errorStream, 'w');
		
		if (!$this->usingWindows) {
			// Check for xterm support
			if (strpos($_SERVER['TERM'],'xterm') !== false) $this->xtermSupport = true;
			
			// Check for bash
			$test = `which bash`;
			if (!empty($test)) $this->bashSupport = true;
		}
		
		// Assign the Argument Variables
		$this->argc = $_SERVER['argc'];
		$this->argv = $_SERVER['argv'];
		
		// Process any passed arguments
		$this->ProcessArguments();
	}
	
	public function __destruct() {
		// Close our sockets
		@fclose($this->outputSocket);
		@fclose($this->inputSocket);
		@fclose($this->errorSocket);
	}
	
	public function Bell() {
		$this->SystemWrite($this->bell);
	}
	
	public function Clear() {
		$this->SystemWrite($this->cls);
		$this->SetXY(1,1);
	}
	
	public function DisableAutoReset() {
		$this->autoReset = false;
	}
	
	public function EnableAutoReset() {
		$this->autoReset = true;
	}
	
	public function GetLine() {
		return preg_replace("(\r\n|\n|\r)", '',fgets($this->inputSocket));
	}
	
	public function GetChar() {
		if ($this->usingWindows || !$this->bashSupport) return fgetc($this->inputSocket);
		
		return trim( `bash -c "read -n 1 -t 10 ANS ; echo \\\$ANS"` );
	}
	
	public function HasXtermSupport() {
		return $this->xtermSupport;
	}
	
	public function MaskedGetLine() {
		$get = preg_replace("(\r\n|\n|\r)", '', `stty -echo; head -n1 ; stty echo`);
		$this->WriteLine();
		return $get;
	}
	
	public function MaskedPrompt($text, $fgColor = null, $bgColor = null, $style = null) {
		$this->Write($text . ' ', $fgColor, $bgColor, $style);
		return $this->MaskedGetLine();
	}
	
	public function Prompt($text, $fgColor = null, $bgColor = null, $style = null) {
		$this->Write($text . ' ', $fgColor, $bgColor, $style);
		return $this->GetLine();
	}
	
	public function SetDefaultForegroundColor($fgColor) {
		$this->defaults['forground'] = $fgColor;
	}
	
	public function SetDefaultBackgroundColor($bgColor) {
		$this->defaults['background'] = $bgColor;
	}
	
	public function SetDefaultStyle($style) {
		$this->defaults['style'] = $style;
	}
	
	public function SetTerminalTitle($title = "CLICommander Terminal") {
		if (!$this->usingWindows) $this->SystemWrite("\033]2;".$title."\007");
	}
	
	public function SetXY($x = 1, $y = 1) {
		if ($x < 1) $x = 1;
		if ($y < 1) $y = 1;
		$this->SystemWrite("\033[{$x};{$y}H");
	}
	
	public function Write($text, $fgColor = null, $bgColor = null, $style = null) {
		if (!$this->usingWindows) {
			// Check our colors and styles
			if (is_array($fgColor)) {
				// User passed a style array
				$style = (isset($fgColor['style']) && !empty($fgColor['style'])) ? $fgColor['style'] : $this->defaults['style'];
				$bgColor = (isset($fgColor['background']) && !empty($fgColor['background'])) ? $fgColor['background'] : $this->defaults['background'];
				$fgColor = (isset($fgColor['foreground']) && !empty($fgColor['foreground'])) ? $fgColor['foreground'] : $this->defaults['foreground'];
			} else {
				// Check for individual options
				if ($fgColor == null) $fgColor = $this->defaults['foreground'];
				if ($bgColor == null) $bgColor = $this->defaults['background'];
				if ($style == null) $style = $this->defaults['style'];
			}
			
			$format = $this->GetFormatString($fgColor, $bgColor, $style);
			
			// Save text with formatting escape sequence
			$text = $format.$text;
			
			if ($this->autoReset) $this->SystemWrite(sprintf($this->escape, 0));
		}
		
		// Write our text to the output socket
		$this->SystemWrite($text);
	}
	
	public function WriteError($text = 'A fatal error has occured!') {
		$this->SystemWrite($text, true);
	}
	
	public function WriteLine($text = '', $fgColor = null, $bgColor = null, $style = null) {
		$this->Write($text, $fgColor, $bgColor, $style);
		$this->SystemWrite($this->nl);
	}
	
	public function WriteTemplate($text) {
		$output = $this->ParseTemplate($text);
		$this->SystemWrite($output);
	}
	/*
	 * All Private Methods Below Here
	 */
	private function ClosestXtermColor($rgbString, $foreground = true) {
		// Replace # sign if there
		$rgbString = str_replace('#','',strtoupper($rgbString));
		
		// Check the length
		if (strlen($rgbString) != 6) {
			if ($foreground) return 231; // White
			return 16; // Black
		}
		
		// Breakout the RGB colors
		$r = hexdec(substr($rgb,0,2));
		$g = hexdec(substr($rgb,2,2));
		$b = hexdec(substr($rgb,4,2));
		
		// Check for Greyscale color
		if ($r == $g && $g == $b) {
			$g = $this->ClosestXtermGrey($r);
			$color = $g.$g.$g;
		} else {
			// Color
			$color = $this->ClosestXtermOctet($r).$this->ClosestXtermOctet($g).$this->ClosestXtermOctet($b);
		}
		
		return $this->xtermColors[$color];
	}
	
	private function ClosestXtermGrey($g = 0) {
		if ($g < 4) return '00';
		if ($g > 243) return 'FF';

		$m = $g % 10;

		if ($m != 8) {
			if ($m > 3 && $m < 8) {
				$g = $g + (8 - $m);
			} else {
				switch ($m) {
					case 3:
						$g--;
					case 2:
						$g--;
					case 1:
						$g--;
					case 0:
						$g--;
					case 9:
						$g--;
				}
			}
		}
		
		unset($m);
		$h = dechex($g);
		unset($g);
		
		if (strlen($h) == 1) {
			return '0'.$h;
		} else {
			return $h;
		}
	}
	
	private function ClosestXtermOctet($c = 0) {
		if ($c >= 0 && $c < 47) {
			return '00';
		} elseif ($c > 46 && $c < 116) {
			return '5F';
		} elseif ($c > 115 && $c < 156) {
			return '87';
		} elseif ($c > 155 && $c < 196) {
			return 'AF';
		} elseif ($c > 195 && $c < 236) {
			return 'D7';
		} else {
			return 'FF';
		}
	}
	
	private function GetFormatString($fgColor, $bgColor, $style) {
		// Initialize the format arrays
		$formats = array(
			'foreground'	=>	'',
			'background'	=>	'',
			'style'	=>	''
		);
		$xFormats = array(
			'foreground'	=>	'',
			'background'	=>	''
		);
		
		// Check the foreground color
		if (array_key_exists($fgColor, $this->foregroundColors)) {
			$formats['foreground'] = $this->foregroundColors[$fgColor];
		} else {
			if ($this->xtermSupport === true) {
				// This is an xterm color
				if (array_key_exists($fgColor, $this->xtermColors)) {
					// Good xterm color
					$xFormats['foreground'] = sprintf($this->escape, '38;5;'.$this->xtermColors[$fgColor]);
				} else {
					if (is_string($fgColor) && (strlen($fgColor == 6) || strlen($fgColor) == 7)) {
						// Convert RGB string to the closest xterm capable color
						$xFormats['foreground'] = sprintf($this->escape, '38;5;'.$this->ClosestXtermColor($fgColor, true));
					} elseif (is_int($fgColor) && $fgColor >= 0 && $fgColor <= 255) {
						// Already passed as xterm color index
						$xFormats['foreground'] = sprintf($this->escape, '38;5;'.$fgColor);
					} else {
						// No valid xterm color found
						$formats['foreground'] = $this->foregroundColors['default'];
					}
				}
			} else {
				// No valid ANSI or xterm color found
				$formats['foreground'] = $this->foregroundColors['default'];
			}
		}
		
		// Check the background color
		if (array_key_exists($bgColor, $this->backgroundColors)) {
			$formats['background'] = $this->backgroundColors[$bgColor];
		} else {
			if ($this->xtermSupport === true) {
				// This is an xterm color
				if (array_key_exists($bgColor, $this->xtermColors)) {
					// Good xterm color
					$xFormats['background'] = sprintf($this->escape, '38;5;'.$this->xtermColors[$bgColor]);
				} else {
					if (is_string($bgColor) && (strlen($bgColor == 6) || strlen($bgColor) == 7)) {
						// Convert RGB string to the closest xterm capable color
						$xFormats['background'] = sprintf($this->escape, '48;5;'.$this->ClosestXtermColor($bgColor, false));
					} elseif (is_int($bgColor) && $bgColor >= 0 && $bgColor <= 255) {
						// Already passed as xterm color index
						$xFormats['background'] = sprintf($this->escape, '48;5;'.$bgColor);
					} else {
						// No valid xterm color found
						$formats['background'] = $this->backgroundColors['default'];
					}
				}
			} else {
				// No valid ANSI or xterm color found
				$formats['background'] = $this->backgroundColors['default'];
			}
		}
		
		// Check the style
		if (array_key_exists($style, $this->styles) && $style != 'default') $formats['style'] = $this->styles[$style]; else unset($formats['style']);
		
		// Build the format string
		$formatString = sprintf($this->escape, implode(';',$formats)) . $xFormats['foreground'] . $xFormats['background'];
		
		return $formatString;
	}
	
	private function ParseFormat($matches) {
		print_r($matches);
		$format = explode('|',strtolower($matches['format']));
		$fg = (isset($format[0])) ? $format[0] : null;
		
		// Check for reset
		if ($fg == 'reset') return sprintf($this->escape, 0);
		
		$bg = (isset($format[1])) ? $format[1] : null;
		$style = (isset($format[2])) ? $format[2] : null;
		
		return $this->GetFormatString($fg, $bg, $style);
	}
	
	private function ParseTemplate($data) {
		return preg_replace_callback('|\{(?P<format>[^\}]+)\}|', array($this, 'ParseFormat'), $data) . $this->nl;
	}
	
	private function ProcessArguments() {
		
	}
	
	private function SystemWrite($text = '', $useErrorSocket = false) {
		if ($useErrorSocket) {
			@fwrite($this->errorSocket, $text);
		} else {
			@fwrite($this->outputSocket, $text);
		}
	}
}

?>