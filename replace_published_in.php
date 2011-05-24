#!/usr/local/bin/php
<?php
include_once("/home/carlosp/data/niklas_db_flickr/conf.php");

$file = file("a");
// open database connections                                                                                                                                            
 $connection = mysql_connect($host, $user, $pass) or die('Unable to connect');                                                                                           
 mysql_select_db($db) or die ('Unable to select database');                                                                                                              
                                                                                                                                                                         

foreach( $file as $line ) {
	$line = explode('|', $line);
	$line[0] = str_replace('"', '', $line[0]);
	$line[1] = str_replace('"', '', $line[1]);
	$line[0] = trim($line[0]);
	$line[1] = trim($line[1]);
	$query = "SELECT notes from vouchers where publishedIn='$line[0]'";
	$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());                                                                                       
	if (mysql_num_rows($result) > 0)  {
		while($row = mysql_fetch_object($result)) {
			$old_notes = $row->notes;
			$search_str = ", " . $line[1];
			$new_notes = str_replace(trim($search_str), "", $line[0]);
			if( $old_notes != "") {
				echo "\nUPDATE vouchers SET notes='$old_notes, $new_notes', publishedIn='$line[1]' WHERE publishedIn='$line[0]';";
			}
			else {
				echo "\nUPDATE vouchers SET notes='$new_notes', publishedIn='$line[1]' WHERE publishedIn='$line[0]';";
			}
		}
	}
}
                                                                                                                                                                         
                                         

?>
