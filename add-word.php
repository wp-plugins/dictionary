<?php
include('functions.php');
$word = $_POST['word'];
$definition = $_POST['definition'];
$successfull_notification_start = '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>';
$error_notification_start = '<div id="setting-error-settings_updated" class="error settings-error"><p><strong>';
$notification_end = '</strong></p></div>';


//if word and definiton are longer than 1 character
if (strlen($word) > 1 && strlen($definition) > 1) {

 	//search if word already exists
	$result = get_key($word, $data[strlen($word)]);

	if ($result !== null) { //update definition if it exists
		$data[strlen($word)][$result]['definition'] = $definition;
		file_put_contents("data/dict.json", json_encode($data));
		echo $successfull_notification_start . 'Word ' . $word . ' was updated' . $notification_end;		
	}
	else { //or add a new word if it doesn't exists
		$data[strlen($word)][] = array('word' => $word, 'definition' => $definition);
		file_put_contents("data/dict.json", json_encode($data));
		echo $successfull_notification_start . 'Word ' . $word . ' was added' . $notification_end;
	}

	
}
else {
	echo $error_notification_start . "Both the word and definition should be longer than 1 symbol" . $notification_end;
}


?>