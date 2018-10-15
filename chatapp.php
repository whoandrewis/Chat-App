#!/usr/local/bin/php -d display_errors=STDOUT
<?php
// permissions at 755
// $cookie_username_value=$_POST['user_name'];
// setcookie("username", $cookie_username_value);
$username=$_POST['user_name'];
echo "<h2 id='username'> $username </h2>";
echo '<?xml version = "1.0" encoding="utf-8"?>';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
	<meta name="authors" content="Andrew Nguyen Pic40A"/> 
	<meta name="description" content="Translating_Chat_App" />
	<meta name= "keywords" content="chat"/>
	<meta name= "copyright" content="Copyright 2018 Andrew Nguyen"/>
	
<title>World Speak</title> 

<link rel="stylesheet" type="text/css" href="chatstyle.css" />

<script type="text/javascript">

			function display_chat(result){
				//alert (result);
				if (result.length==0){
					return;
				}
				
			//result is an long string
			displayarea_node=document.getElementById("display_area");
			displayarea_node.innerHTML="";
			result_arr=result.split(";"); // each element is a different message


			for (i=0; i< result_arr.length-1; ++i){
				temp=result_arr[i].split(",");
				user=temp[0];
				message=temp[1];
				//alert (message);
				final_display=user+ " : " + message;
			// keep pulling the final string out, each time should override then 
				if (message !='' || message != undefined){
				x=document.createElement("p");
				x.setAttribute("class","txt_message");
				y=document.createTextNode(final_display); // when line is null 
				// textnode
				x.appendChild(y);
				displayarea_node.appendChild(x);
				// the order that they're appende
				}
				}
			}
			function ajax_reading() 
			{
				var xhr = new XMLHttpRequest();

				xhr.onreadystatechange = function () 
				{

					if (xhr.readyState == 4 && xhr.status == 200) 
					{
						var result = xhr.responseText;
						display_chat(result);
					}
				}
				xhr.open("POST", "request_database.php",true); 
				xhr.send(); 
			}

			function init_reading(){
				user=document.getElementById("username");
				username_value=user.innerHTML;
				username_value="user=" + username_value; 

				cookie=document.cookie;
				cookie=username_value;
				// alert(username_value);

				x=setInterval(ajax_reading,100);
			}
	
// Ajax Engine

			function process_submit(){
				username_node=document.getElementById("username");
				username_entry=username_node.innerHTML;
				username_entry=username_entry.trim(); 

				message_node=document.getElementById("input_field");
				message=message_node.value;
				
				//alert(username_entry);
				//alert(message);
				if (username_entry.length!=0){// check that something is actually there
				query= "send" + "=" + username_entry+ ","+ message;
				ajax_writing(query);

				}	
			}
// Ajax Engine
			function ajax_writing(query) // triggers when push send
			{
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "chatchat.php",true); 
				//alert (query);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.send(query); // modify send
			}
		
</script>

</head>
<body onload="init_reading()" style="background-color: #E0FFFF">

<?php
// script to process login info, and add to the database
// add the if (isset($_POST[' '])), then do all this
// permissions at 755
try 
{
	$database="chat.db";
	$db= new SQLite3($database);
}
catch (Exception $exception)
{
	echo '<p> There was an error connection to the database!</p>';
	
	if ($db)
	{
		echo $exception-> getMessage();
	}
}
// create the user_info table
$table='user_info';
$field1='user_name';
$field2='user_password';
$field3='messages_displayed';

$sqlcreate="CREATE TABLE IF NOT EXISTS $table (
$field1 varchar(1000000),
$field2 varchar(1000000),
$field3 int(9) DEFAULT 0
)";

$result=$db->query($sqlcreate);

// create the group_chat table
$table2="group_chat";
$fielda="user";
$fieldb="message";
$fieldc="time";
$fieldd="number_of_entries";

$sql="CREATE TABLE IF NOT EXISTS $table2 (
$fielda varchar(10000000),
$fieldb varchar(10000000),
$fieldc varchar(10000000),
$fieldd int(9) DEFAULT 0
)";
$result=$db->query($sql);


$new=$_POST['vote'];
$name=$_POST['user_name'];
$name=str_replace(' ', '',$name);
// remove all white space, because if do not want blank usernames
$password=$_POST['user_password'];

$sql="SELECT count(*) FROM 'user_info' WHERE '$field1'='$name'";
$result=$db->query($sql);
$record=$result->fetchArray();
$count=$record['count(*)'];
//echo "<p> $count </p>";
// need to fix the password thing for a new user and for one that is already taken
// change to count 
if ($count>0)// there is a match
	{
		echo "<p> That username is already taken </p>";
	}	
else if ($name=='')
	{
		echo "<p> Input a valid username, no spaces, if you want to chat </p>";
	}	
else{ // this would be $new is false, so add the login and password into the database
	$sql="INSERT INTO $table ($field1, $field2) VALUES ('$name', '$password')";
		$result=$db->query($sql);
	}

echo "<div id='chatbox'>";

	echo "<div id='display_area'>";
// text would display here
// insert textNodes and append here 
	echo "</div>";

		echo "<form action='#' method='post'>";
		echo "<div id='text_submit'>";
			echo "<input type='text' name='message' class='text_input' id='input_field'/>";
			echo "<input type='button' value='Submit' class='text_input' onclick='process_submit()' id='submit_button'/>";
		echo "</div>";
		echo "</form>";
//		
echo "</div>";
// when someone types then it would send to chatchat to be put onto the database

?>

</body>
</html>