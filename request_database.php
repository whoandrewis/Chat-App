#!/usr/local/bin/php -d display_errors=STDOUT
<?php
// to pull up chat history, call once, then setInterval on new_database.php
try 
{
	$db= new SQLite3('chat.db');
}
catch (Exception $exception)
{
	echo '<p> There was an error connection to the database!</p>';
	
	if ($db)
	{
		echo $exception-> getMessage();
	}
}

$table="group_chat";
$field1="user";
$field2="message";
$field3="time";
$field4="number_of_entries";
//number of entries should only have one record, the first one

//should count all the messages and insert that number
// then display the most recent 20 entries

// $sql="SELECT count ($field2) FROM $table";
// $result=$db->query($sql);
// $record=$result->fetchArray();
// $count=$record['count($field2)'];
//

$sqlselect="SELECT * FROM $table"; // selecting empty rows
$result=$db->query($sqlselect);
$final_string="";
while($record=$result->fetchArray()){  // this is on an interval, so then if one thing is in the database, then repeatedly pull up
	echo "$record[user]". " "."$record[time]".","."$record[message]".";";
	// demarcated by the semicolon
// keep a counter of number of messages displayed for each user, and counter for number of messages total, whenever someone presses submit, increments the grand total, in the request_database, if counter number displayed is less, it will display the rest
// display the most recent 30
}
// use this to pull everything out of the chat, and then another ajax to pull new stuff
// check who is online, by making an extra field with 0 or 1 saying if they're online or not
?>
