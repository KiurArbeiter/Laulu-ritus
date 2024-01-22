<?php
$serverinimi="d125315.mysql.zonevs.eu";
$kasutajanimi="d125315_test123";
$parool="TEST123!TEST123";
$andmebaas="d125315_test123";
$yhendus=new mysqli($serverinimi, $kasutajanimi, $parool, $andmebaas);
$yhendus->set_charset("UTF8");