<?php 

	session_start();

	$prepath = "../";
    include "../connect.php";
	include "../functions.php";

	if ($_SESSION['lang'] !== "EN")
	{
		include "../".strtolower($_SESSION['lang'])."/global.php";
		include "../".strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include "../global.php";
		include "../getDATA.php";
	}

	$INFO     = array("response" => false);
	$DATA     = array();
	$RESPONSE = array("info" => &$INFO, "data" => &$DATA);

	$domainMAIN = $domain;
	$domain = $_SERVER["DOCUMENT_ROOT"];

	// Begin with upload
	if ( isset($_FILES["picture"]) )
	{
		$validextensions = array("jpeg", "jpg", "png");
		$temporary       = explode(".", $_FILES["picture"]["name"]);
		$file_extension  = end($temporary);

		//echo 0;
		if (!(($_FILES["picture"]["type"] === "image/png") || ($_FILES["picture"]["type"] === "image/jpg") || ($_FILES["picture"]["type"] === "image/jpeg")))
		{
				//echo 1;
			$INFO["response"]   = true;
			$INFO["uploaded"]   = false;
			$INFO["errorCode"]  = 1;
			$INFO["hasMessage"] = true;
			$PRINT_MESSAGE = (object) array(
			                                "open" => true,
			                                "messageType" => "danger",
			                                "messageText" => $lang->aj_allowedExtensions." <span class=\"strong\">.jpg</span>, <span class=\"strong\">.png</span>, <span class=\"strong\">.jpeg</span>",
			                                "messageIcon" => "fa-times"
			                                );
			include "../requests/printMessage.php";
			$DATA["message"] = $PRINT_MESSAGE->final; $DATA["timeout"] = 8000;

		} elseif (floatval($_FILES["picture"]["size"]) > 5*1024*1024) // =5MB
		{
			$INFO["response"]   = true;
			$INFO["uploaded"]   = false;
			$INFO["errorCode"]  = 2;
			$INFO["hasMessage"] = true;
			$PRINT_MESSAGE = (object) array(
			                                "open" => true,
			                                "messageType" => "danger",
			                                "messageText" => $lang->aj_maxUploadSize." 5MB.",
			                                "messageIcon" => "fa-times"
			                                );
			include "../requests/printMessage.php";
			$DATA["message"] = $PRINT_MESSAGE->final; $DATA["timeout"] = 8000;

		} elseif (!in_array($file_extension, $validextensions))
		{
			$INFO["response"]   = true;
			$INFO["uploaded"]   = false;
			$INFO["errorCode"]  = 3;
			$INFO["hasMessage"] = true;
			$PRINT_MESSAGE = (object) array(
			                                "open" => true,
			                                "messageType" => "danger",
			                                "messageText" => $lang->aj_allowedExtensions." <span class=\"strong\">.jpg</span>, <span class=\"strong\">.png</span>, <span class=\"strong\">.jpeg</span>",
			                                "messageIcon" => "fa-times"
			                                );
			include "../requests/printMessage.php";
			$DATA["message"] = $PRINT_MESSAGE->final; $DATA["timeout"] = 8000;


		} else // Passed on check conditions
		{
			//echo 5;
			if ($_FILES["picture"]["error"] > 0)
			{
				$INFO["response"]   = true;
				$INFO["uploaded"]   = false;
				$INFO["errorCode"]  = 5;
				$INFO["hasMessage"] = true;
				$PRINT_MESSAGE = (object) array(
				                                "open" => true,
				                                "messageType" => "danger",
				                                "messageText" => $lang->aj_errorOccured,
				                                "messageIcon" => "fa-times"
				                                );
				include "../requests/printMessage.php";
				$DATA["message"] = $PRINT_MESSAGE->final; $DATA["timeout"] = -1;
			} else
			{
				//echo $USER->image; exit();
				$oldImage = $USER->image ? $DIR . "/img/users/".$USER->image : NULL;
				//echo $USER->image;
				$newName  = _SQL_escape_exstring(["SELECT * FROM users WHERE BINARY image = '","' AND BINARY username = '".$USER->username."'"],20).".".$file_extension;

				//echo $newName;

				// Storing source path of the file
				$sourcePath = $_FILES['picture']['tmp_name'];
				// Target path
				$targetPath = "../img/users/".basename($_FILES["picture"]["name"]);
				move_uploaded_file($sourcePath, $targetPath); 		// Moving Uploaded file
				if (rename($targetPath, "../img/users/".$newName).".".$file_extension)
				{
					if ($oldImage) unlink($oldImage);
					$sql = mysqli_query($CONNECTION,"UPDATE users SET image = '".$newName."' WHERE BINARY username = '".$USER->username."'");
				//      INFO
				// ==============
					$INFO["response"]   = true;
					$INFO["uploaded"]   = true;
					$INFO["hasMessage"] = true;
					$INFO["errorCode"]  = -1;
				// ==============
				//     DATA
				// ==============
					$PRINT_MESSAGE = (object) array(
					                                "open" => true,
					                                "messageType" => "info",
					                                "messageText" => $lang->aj_succUploaded,
					                                "messageIcon" => "fa-check"
					                                );
					include "../requests/printMessage.php";
					$DATA["message"] = $PRINT_MESSAGE->final; $DATA["timeout"] = -1;
					$DATA["image"]   = make_image($newName, $FILE);
				} else
				{
					unlink($FILE."img/users/".$newName); // Delete new uploaded file
					$INFO["response"]  = true; $INFO["uploaded"] = true; $INFO["errorCode"] = 4; 
					$INFO["hasMessage"] = true;
					$PRINT_MESSAGE = (object) array(
					                                "open" => true,
					                                "messageType" => "danger",
					                                "messageText" => $lang->aj_errorOccured,
					                                "messageIcon" => "fa-times"
					                                );
					include "../requests/printMessage.php";
					$DATA["message"] = $PRINT_MESSAGE->final; $DATA["timeout"] = -1;
				};							
			};
		};
	};

	echo json_encode($RESPONSE);


?>