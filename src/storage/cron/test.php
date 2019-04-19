<?php
// header("Location: http://venezia-online.com.ua/1cimport");
file_get_contents('http://venezia-online.com.ua/1cimport');
    $r = 'a'.rand().'php';
    fopen($r ,"w");
    //fclose($r);
?>