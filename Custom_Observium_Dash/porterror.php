<?php
session_start();
include_once 'functions.php';
$urls = array($GLOBALS['deviceurl'], $GLOBALS['porterrorurl']);
performAsyncRequest($urls);
getHosts(0);
getNetErrors(1);

?>

