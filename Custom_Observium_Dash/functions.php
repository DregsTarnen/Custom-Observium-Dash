<?php

include 'values.php';
    
function setCurlOptions(&$mulreq, &$requests, $urls) {
    $usr = $GLOBALS['usr'];
    $pwd = $GLOBALS['pwd'];
    foreach ($urls as $url) {
        $curl = curl_init();
        //set url
        curl_setopt($curl, CURLOPT_URL, $url);
        //return transfer as string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //set login
        curl_setopt($curl, CURLOPT_USERPWD, $usr.":".$pwd);
        //enable gzip compression
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_multi_add_handle($mulreq, $curl);
        $requests[$url] = $curl; 
    }
} //end of function
        
function performAsyncRequest($urls) {
    $mulreq = curl_multi_init();
    $requests = array();
    setCurlOptions($mulreq, $requests, $urls);
    $active = null;
    do { $mrc = curl_multi_exec($mulreq, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($mulreq) == -1) {
            continue; }
        do {
            $mrc = curl_multi_exec($mulreq, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
    foreach ($requests as $request) {
        $data = curl_multi_getcontent($request);
        array_push($GLOBALS['results'], $data);
        curl_multi_remove_handle($mulreq, $request);
    }
    curl_multi_close($mulreq);
} //end of function


//this function gets the devices and produces boxes in the color red or green indicating up or down status
function getDevices($type, $container) {
    $url = $GLOBALS['deviceurl'];
    $usr = $GLOBALS['usr'];
    $pwd = $GLOBALS['pwd'];
    $data = getRequest($url, $usr, $pwd);
    $json = json_decode($data, true);
    $devices = $json['devices'];
    foreach ($devices as $device) {
        if ($device['type'] == $type) {
            $host = $device['hostname'];
            $device_status = $device['status'];
            makeDeviceDiv($device_status, $container, $host);
        }
    }
} //end of function

function makeDeviceDiv($device_status, $container, $host) {
    switch ($device_status) {
        case 1:
            echo "<div id='container_".$container."'>";
            echo "<div class='greenbox'><span>".$host."</span></div>";
            echo "</div>";
            break;
        case 0:
            echo "<div id='container_".$container."'>";
            echo "<div class='redbox'><span>".$host."</span></div>";
            echo "</div>";
            break;
        default:
            break;
        }      
} //end of function

//the following functions allow us to get port errors
function getNetErrors($call) {
    $portdata = $GLOBALS['results'][$call];
    $json = json_decode($portdata, true);
    $ports = $json['ports'];
    foreach ($ports as $port) {
        $adminstatus = $port['ifAdminStatus'];
        if ($adminstatus == "up") {
            $inerrors = $port['ifInErrors_delta'];
            $outerrors = $port['ifOutErrors_delta'];
            $totalerrors = $inerrors+$outerrors;
            $deviceid = $port['device_id'];
            $portlabel = $port['port_label'];
            $host = $GLOBALS['hostnames'][$deviceid];
            sortErrors($totalerrors, $host, $portlabel);
        } 
    }
}

function sortErrors($totalerrors, $host, $portlabel) {
    if ($totalerrors > 0) {
        echo "<div id='container_port'>";
        echo "<div class='yellowboxerror'>".$host."<br/><span style='font-weight: normal;'>".$portlabel."</span></div>";
        echo "</div>"; 
    } elseif ($totalerrors > 5 && $totalerrors < 10) {
        echo "<div id='container_port'>";
        echo "<div class='orangeboxerror'>".$host."<br/><span style='font-weight: normal;'>".$portlabel."</span></div>";
        echo "</div>";
    } elseif ($totalerrors > 10) {
        echo "<div id='container_port'>";
        echo "<div class='redboxerror'>".$host."<br/><span style='font-weight: normal;'>".$portlabel."</span></div>";
        echo "</div>";
    }
}
//end of port error functions


//functions to get room sensor data current room sensor 
function getRoomAlertData($call) {
    $data = $GLOBALS['results'][$call];
    $json = json_decode($data, true);
    $sensors = $json['sensors'];
    foreach ($sensors as $sensor) {
        $id = $sensor['device_id'];
        $host = $GLOBALS['hostnames'][$id];   
        $warning = (9.0/5.0*$sensor['sensor_limit_warn']+32);
        $sensorvalue = (9.0/5.0*$sensor['sensor_value']+32);
        $critical = (9.0/5.0*$sensor['sensor_limit']+32);
        makeRoomAlertDiv($host, $sensorlimit, $warning, $critical, $sensorvalue);
    }
}

function makeRoomAlertDiv($host, $sensorlimit, $warning, $critical, $sensorvalue) {
    if ($sensorvalue >= $critical) {
        echo "<div id='container_alert'>";
        echo "<div class='redboxalert'>".$host."<br/><span style='font-weight: normal;'>Temp- ".$sensorvalue." F<br/>Temp Limit- ".$critical." F</span></div>";
        echo "</div>"; 
    } elseif ($sensorvalue >= $warning && $sensorvalue < $critical ) {
        echo "<div id='container_alert'>";
        echo "<div class='orangeboxalert'>".$host."<br/><span style='font-weight: normal;'>Temp- ".$sensorvalue." F<br/>Temp Limit- ".$critical." F</span></div>";
        echo "</div>";
    } else {
        echo "<div id='container_alert'>";
        echo "<div class='greenboxalert'>".$host."<br/><span style='font-weight: normal;'>Temp- ".$sensorvalue." F<br/>Temp Limit- ".$critical." F</span></div>";
        echo "</div>";
    }
}


//get all the hostnames and place them in an array
function getHosts($call) {
    $data = $GLOBALS['results'][$call];
    $json = json_decode($data, true);
    $hosts = $json['devices'];
    foreach ($hosts as $host) {
        $GLOBALS['hostnames'][$host['device_id']] = $host['hostname'];
    }
} //end of function

//###################################################
//function getRequest performs a single http request at a time
function getRequest($url, $usr, $pwd) {
    //initialize curl
    $curl = curl_init();
    //set url
    curl_setopt($curl, CURLOPT_URL, $url);
    //return transfer as string
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //set login
    curl_setopt($curl, CURLOPT_USERPWD, $usr.":".$pwd);
    //enable gzip compression
    curl_setopt($curl, CURLOPT_ENCODING, '');
    // contains return string
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
} //end of function



?>

