<?php
include('functions.php');

//assigning needed variables
$words_to_show = $_POST['words_to_show'];
$word_override = $_POST['word_override'];

if ($word_override != '') {
	$definition = get_definition($word_override, $data);
	if ($definition) {
		show($word_override, $definition);
	}
	else {
		echo "<p>Word <strong>$word_override</strong> was not found :(</p>";
	}
}
else {
	//cleaning the result array. Just in case
	$random_words = array();

	while (count($random_words) < $words_to_show) {
		$random_word_lenght_key = array_rand($data, 1);
		$words_data = $data[$random_word_lenght_key];
		$random_word_key = array_rand($words_data, 1);
		$single_word_data =  $words_data[$random_word_key];
		if (!array_key_exists($single_word_data['word'], $random_words))
			$random_words[$single_word_data['word']] = $single_word_data['definition'];
	}

	foreach ($random_words as $word => $definition) {
		show($word, $definition);
	}
}



?>