#!/usr/local/bin/php
<?php
function generateCharacter ()
{
$possible = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
return $char;
}

function generateGUID ()
{
$GUID = generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
$GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
$GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
$GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
$GUID = $GUID .generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter();
return $GUID;
}

$i = 0;
while ($i < 100) {
	$GUID = generateGUID();
	echo $GUID. "\n";
	$i++;
}


?>
