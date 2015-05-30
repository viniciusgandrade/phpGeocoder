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
$statement = $db->prepare('SELECT chave,latitude,longitude from filtro_localidade');
$sqlite3result = $statement->execute();
while($array = $sqlite3result->fetchArray()) { 
$str = array();
$chave;$cosLat;$cosLong;$sinLat;$sinLong;
for($i = 0; $i < $sqlite3result->numColumns(); $i++) { 
        $name = $sqlite3result->columnName($i); 
        $value = $array[$name];
        if($name == "chave") {
	   		$chave = $value;
		} else if($name == "latitude") {
			$cosLat = cos(deg2rad(floatval($value)));
			$sinLat = sin(deg2rad(floatval($value)));
		} else if($name == "longitude") {
			$cosLong = cos(deg2rad(floatval($value)));
			$sinLong = sin(deg2rad(floatval($value))); 
		}
}

echo "UPDATE filtro_localidade SET sin_latitude = '".$sinLat."', sin_longitude = '".$sinLong."', cos_longitude = '".$cosLong."', cos_latitude = '".$cosLat."' WHERE chave = ".$chave.";"; 
echo "<br>";
}
?>