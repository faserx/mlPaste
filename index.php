
<?php

include "core.php";

$ml = new mlPaste();

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
	if((isset($_GET['id'])) && (!empty($_GET['id'])))
	{
		if(isset($_GET['shl']))
		{
			$shl = $ml->showHlPaste($_GET['id']);
			if($shl == NULL)
				echo "Source not found!";
		   else
				echo $shl;
		}else
		{
			header("Content-Type: text/plain");
			$raw = $ml->showRawPaste($_GET['id']);
			if($raw == NULL)
				echo "Source not found!";
			else
				echo $raw;
		}
	}else 
		show_index();
}else if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if((isset($_POST['paste'])) && (!empty($_POST['paste'])))
	{
		$paste = $ml->addPaste($_POST['paste'], $_SERVER['REMOTE_ADDR']);
		echo $paste;
	}else
			show_index();
}else
	show_index();
	
function show_index()
{
	echo'
	<form method="POST" action="index.php">
	<textarea name="paste"></textarea>
	<input type="submit" value="invia"></form>';
	echo "mlPaste";
}
?>
