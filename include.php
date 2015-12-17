<?php

//LANCEMENT D'UNE SESSION
session_start();

// connexion à la base de données
$mysql = mysql_connect("localhost","dev","dev");
mysql_select_db("dev", $mysql);

//DEFINITION DU FUSEAU HORRAIRE
date_default_timezone_set('Europe/Paris');

?>