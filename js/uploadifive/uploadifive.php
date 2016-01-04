<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// Set the uplaod directory
$uploadDir = __DIR__ . '/../../images/upload/' . substr($_POST['timestamp'], 0, 4) . '/';
if(!is_dir($uploadDir)){
    mkdir($uploadDir);
    chmod($uploadDir, 0777);
}


$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
        include_once __DIR__ . '/../../vendor/nette/utils/src/Utils/Strings.php';
    
	$tempFile   = $_FILES['Filedata']['tmp_name'];
        
        $fileName = explode('.', $_FILES['Filedata']['name']);
        $postFix = $fileName[count($fileName) - 1];
        unset ($fileName[count($fileName) - 1]);

        $fileName = $_POST['timestamp'] . '_' . Nette\Utils\Strings::webalize(implode('.', $fileName)) . '.' . $postFix;
        $targetFile = $uploadDir . $fileName;
        
	//$targetFile = $uploadDir . $_FILES['Filedata']['name'];

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
        // Save the file
        if(move_uploaded_file($tempFile, $targetFile) == TRUE){
            echo $fileName;
        }else{
            echo 'ERROR';
        }

}else{
    echo 'ERROR - token';
}