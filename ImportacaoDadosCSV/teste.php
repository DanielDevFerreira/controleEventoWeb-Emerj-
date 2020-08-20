<?php

// teste depois para tira duplicidade do arquivo CSV

function getCSV($name) {
$file = fopen($name, "r");
$result = array();
$i = 0;
while (!feof($file)):
if (substr(($result[$i] = fgets($file)), 0, 10) !== ';;;;;;;;') :
$i++;
endif;
endwhile;
fclose($file);
return $result;
}

function getLine($array, $index) {
return explode(';', $array[$index]);
}

$foo = getCSV('foo.csv');
$foo = array_unique($foo); // remove os repetidos
for ($i = 0; $i < count($foo); $i++) {
$line = getLine($foo, $i);
if (!empty($line[0]) && trim($line[1]) == 's') { // válida se tem valor e é ativo.
print_r($line); // faça os insert's aqui
}
}


?>