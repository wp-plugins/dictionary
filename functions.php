<?php

//get data from dict.json
$data = file_get_contents("data/dict.json", "r");

//convert data to array
$data = json_decode($data, true);

function show($word, $definition) {
	
	$before_word = '<p><strong>';
	$after_word = '</strong>';
	$separator = '....';
	$before_definition = '<em>';
	$after_definition = '</em></p>';

	echo $before_word;
	echo $word;
	echo $after_word;
	echo $separator;
	echo $before_definition;
	echo $definition;
	echo $after_definition;
}


function get_definition($word, $array) {

	if (strlen($word) > 1) {
		$key = get_key($word, $array[strlen($word)]);
		if ($key !== null) return $array[strlen($word)][$key]['definition'];
		else return null;

	}
	
}

function get_key($word, $array) {

	if (is_array($array)) {

		foreach ($array as $key => $val) {

		   if (strtolower($val['word']) == strtolower($word)) {
		       return $key;
		   }

		}

	}
	
  	return null;
}