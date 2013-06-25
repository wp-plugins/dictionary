<?php

include('functions.php');
$word = $_POST['term'];


$definition = get_definition($word, $data);
if ($definition !== null) {
	show($word, $definition);
}
else {
	echo "<p>Word <strong>$word</strong> was not found :(</p>";
}


?>