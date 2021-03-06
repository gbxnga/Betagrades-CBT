<?php
require 'includes/config.inc.php';
require MYSQLI ;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['submit'] === 'register_school') {
	extract($_POST);
	if (is_uploaded_file($_FILES['image']['tmp_name']) && ($_FILES['image']['error'] === UPLOAD_ERR_OK)) {
		$errors = array();
		$file_name = $_FILES['image']['name'];
		$file_size = $_FILES['image']['size'];
		$file_tmp = $_FILES['image']['tmp_name'];
		$file_type = $_FILES['image']['type'];

		// explode file name delimited by fullstop,
		// and extract the file extension
		$file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));

		// declare accepted file extensions
		$extensions = array("jpg", "png", "jpeg", "JPEG", "JPG", "PNG");

		// check if file extension matches accepted extensions
		if (!in_array($file_ext, $extensions)) {

			$errors[] = "Extension not allowed";
			echo 'Extension not allowed';
		}

		// if file size is larger than 2MB,
		// declare and error
		if ($file_size > 2097152) {

			$errors[] = "File size exceeded 2MB";
			echo 'File size exceeded 2MB';
		}

		// if there are no errors,
		// move uploaded files
		if (empty($errors)) {

			// Create a tmp_name for the file:
			//$tmp_name = sha1($_FILES['img']['name']) . uniqid('',true);
			$tmp_name = $_FILES['image']['name'];

			// Move the file to its proper folder but add _tmp, just in case:
			//$dest =  IMGS_DIR . $tmp_name . '.'.$file_ext;
			$dest =  IMAGE_DIR . $tmp_name ;

			if (move_uploaded_file($file_tmp, $dest)){
				
				// set file name into a session variable
				// to be used during upload to DB
				$image_name = $tmp_name;
				echo 'Image successfully uploaded ' ;

				// file has been successfully uploaded

			}else {
				echo "Image couldn't be moved, please try again";
			}

		}else { // if there are errors, display them

			print_r($errors);
		}
	}else {
		echo "No image uploaded for this question <br/>";
	}
	
	$q = "INSERT INTO school (name, logo) VALUES ('$schname', '$image_name' )";
	$r1 = mysqli_query($dbc, $q);
	if ($r1 ) {
		header("Location: Home");
		exit();
	}
}