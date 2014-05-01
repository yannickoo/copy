<?php

	// connect to database
	$database = new SQLite3("database.sqlite");
	if(!$database)
		die("Error connecting to database");


	// add new entry
	if(isset($_POST["submit"]))
	{
		$message = SQLite3::escapeString($_POST["message"]);
		$query = $database->query("INSERT INTO entries VALUES (NULL,'".time()."','".$message."')");

		if(!$query)
			die("Data could not be saved.");
		else
			header("location: index.php");
	}


	// Delete message
	if(isset($_GET["delete"]))
	{
		$query = $database->query("DELETE FROM entries WHERE entry_id = '".trim(addslashes(SQLite3::escapeString($_GET["delete"])))."'");
		if($query)
			header("location: index.php");
		else
			die("Error deleting item");
	}


	// Show existing messages
	$content = "<ul>\n";
	$query = $database->query("SELECT * FROM entries ORDER BY entry_id DESC LIMIT 10");
	if($query)
	{
		while($message = $query->fetchArray())
		{
			$content .= "<li><input type=\"text\" value=\"".$message["message"]."\"> ".date("d.m.Y H:i",$message["timestamp"]).' <a href="?delete='.$message["entry_id"].'">Delete</a>'."</li>\n";
		}
	}
	$content .= "</ul>\n";


	// Load template and show content
	$template = file_get_contents("template.html");
	$template = str_replace("#{content}",$content,$template);
	echo $template;
?>