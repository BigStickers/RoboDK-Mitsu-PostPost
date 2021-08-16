<?php
/*
BigStickers/RoboDK-Mitsu-PostPost is licensed under the

GNU General Public License v3.0
Permissions of this strong copyleft license are conditioned on making available complete source code of licensed works and modifications, which include larger works using a licensed work, under the same license. Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.

Copyright Big Stickers and Signs, 2021. 
*/
$file = file("input.txt");
$outFile = Array(); 
$lastPos = ""; 
$commandLines = Array();

foreach($file as $index => $line)
{
	$lineOutput = ""; 
	if(preg_match("/(\d\d?\d?\d?)( )(P\d\d?\d?\d?\d?)(=)/",$line))
	{
		if(!preg_match("/(\(6\,0\))/", $line))
		{
			//need to append to these lines: 
			$tmp = str_replace("\r","(6,0)\r",$line);
			
			$lineOutput = $tmp;
		}
		else
		{
			$lineOutput = $line; 
		}
	}
	else if(stristr($line,"mvc"))
	{
		$lineOutput = str_ireplace("Mvc","Mvr",$line); 
		$commandLines[] = Array("line" => "$index", "type" => "arc");
	}
	else if(stristr($line,"Ovrd"))
	{
		$lineOutput = str_ireplace("ovrd","spd",$line); 
	}
	else if (stristr($line, "mvs"))
	{
		$lineOutput = $line;
		$commandLines[] = Array("line" => "$index", "type" => 'linear');
		
	}
	else 
	{
		$lineOutput = $line;
		
	}
	
	$outFile[] = $lineOutput; 
	
}

$arcCount = 0; 
$lastLinear = 0; 
$suppressLines = Array(); 

foreach($commandLines as $index => $command)
{
	if($command['type'] == "arc")
	{
		$arcCount++;
	}
	else{
		$arcCount = 0; 
		$lastLinear = $index;
	}
	if($arcCount > 3)
	{
		//echo $lastLinear . " - " . $index . "\n";
		$commandLines[$index] = Array("type" => "arc", "line" => $command['line'], "supress" => true);
		$suppressLines[] = $commandLines[$index - 1]['line'];
		$suppressLines[] = $commandLines[$index - 2]['line'];
		$suppressLines[] = $commandLines[$index - 3]['line'];
	}
}

foreach($outFile as $index => $line)
{
	if(in_array($index, $suppressLines))
	{
		echo "'" . $line; 	
	}
	else{
		echo $line;
	}
	
	
}




?>
