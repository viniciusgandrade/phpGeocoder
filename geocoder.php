<!DOCTYPE HTML>
<html manifest="" lang="pt-BR">
<head>
<meta charset="UTF-8">
</head>

<?php
require __DIR__.'/vendor/autoload.php';

$curl     = new \Ivory\HttpAdapter\CurlHttpAdapter();
$geocoder = new \Geocoder\Provider\GoogleMaps($curl);

$db = new SQLite3('../mec_prouni/Prouni-2015-1.sqlite');
$statement = $db->prepare('SELECT distinct municipio, uf_sigla from oferta');
$sqlite3result = $statement->execute();
while($array = $sqlite3result->fetchArray()) { 
$str = array();
$cidade_nome;
for($i = 0; $i < $sqlite3result->numColumns(); $i++) { 
        $name = $sqlite3result->columnName($i); 
        $value = $array[$name];
        if($name == "municipio") {
	   $cidade_nome = $value;
	}
        array_push($str,$value);
}
$cidade = implode(" - ", $str);
$cidade = $cidade.", Brasil";
//echo $cidade."<br>";
$arr = $geocoder->geocode($cidade);
$position = $arr->all()[0]->getCoordinates();

echo "UPDATE filtro_localidade SET latitude = '".$position->getLatitude()."', longitude = '".$position->getLongitude()."' WHERE nome = '".$cidade_nome."';"; 
echo "<br>";
//echo $position->getLatitude();
//echo ",";
//echo $position->getLongitude();
}
?>
