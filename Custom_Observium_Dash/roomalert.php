<?php
session_start();
include_once 'functions.php';
$urls = array($GLOBALS['senshosturl'], $GLOBALS['sensorurl']);
performAsyncRequest($urls);
getHosts(0);
getRoomAlertData(1);

?>
