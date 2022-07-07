<?php
include('connect.php');

function semicolonCheck($prog)
{
	global $lines;
	$lines = preg_split('/\r\n|\r|\n/', $prog);
	for($i = 0; $i < sizeof($lines); $i++)
	{
		if(strlen($lines[$i]) > 0 && $lines[$i][strlen($lines[$i]) - 1] != ';')
		{
			echo 'YD says: missing semicolon at line no. ' . ($i+1) . '!! ' . '{ ' .$lines[$i] . ' }';
			return 0;
		}
	}
	return 1;
}

function isyd()
{
	global $lines;

	for($i = 0; $i < sizeof($lines); $i++)
	{
		$str = '';
		if(strlen($lines[$i]) > 0)
		{
			for($j = 0; $j < 8; $j++)
			{
				if($j < strlen($lines[$i]))
				{
					$str = $str . $lines[$i][$j];
				}
			}

			if($str != 'hey yd: ')
			{
				echo 'YD says: No one said hello to me at line no. ' . ($i + 1) . '!! ' . '{ ' .
				$lines[$i] . ' }';
				return 0;	
			}
		}
	}
	return 1;
}

$prog = $_POST['prog'];
global $lines;
$lines = array();
if(semicolonCheck($prog))
{
	if(isyd())
	{
		$s='code.py';
		$out = fopen($s,'w');
		for($i = 0; $i < sizeof($lines); $i++)
		{
			$flag = 0;
			for($j = 8; strlen($lines[$i]) > 0 and $lines[$i][$j] != ';'; $j++)
			{
				$flag = 1;
				fwrite($out, $lines[$i][$j]);
			}
			if($flag)
				fwrite($out,';'."\n");
		}
		fclose($out);
		$output =shell_exec("python3 code.py 2>&1");
		$outArr = explode(PHP_EOL, $output);
		echo "YD says: ";
		for($i = 0; $i < sizeof($outArr); $i++)
		{
			echo $outArr[$i];
			echo "<br>";
		}
	}
}
?>