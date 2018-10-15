#!/usr/local/bin/php -d display_errors=STDOUT
<?php
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
// chat will go one after the other like in the order of a normal chat

$sqlcreate="CREATE TABLE IF NOT EXISTS $table (
$field1 varchar(10000000),
$field2 varchar(10000000),
$field3 varchar(10000000),
$field4 int(9) DEFAULT 0
)";

$result=$db->query($sqlcreate);

// timer ajax to write to the other Aja
// two different Ajax, one for writing and one for reading
// writing only triggers when push send
// reading is triggering constantly
// there would be php (for writing), php (for reading)
// group chat is ok
// maybe Ajax number 3 and that one is constantly checking users online, to put on who's online
// large group chat but everyone still gets an ID 

// one for reading

// next would be to take the string and then
$username_and_text=$_POST['send']; //send=A,Hello 
//$username_and_text=$_REQUEST['send'];
$username_and_text=explode(',',$username_and_text);
// change the character used for demarcation, otherwise will have errors if the message contains commmas 
$username=$username_and_text[0];
$text=$username_and_text[1];

date_default_timezone_set('America/Los_Angeles');
$timestamp=time();
$time=date("m/d/y h:i:s A", $timestamp);

$sqlinsert="INSERT INTO $table ($field1, $field2, $field3) VALUES ('$username', '$text', '$time')";
$result=$db->query($sqlinsert);

// update the total messages count 
// get the total messages and then UPDATE

$sqlselect="SELECT $field4 FROM $table";
$result=$db->query($sqlselect);
$record=$result->fetchArray(SQLITE3_ASSOC);
$updated_entries=$record[$field4];
++$updated_entries;

$sqlupdate="UPDATE $table SET $field4='$updated_entries'";
$result=$db->query($sqlupdate);
// now need to insert into the table the $username and the message

?>