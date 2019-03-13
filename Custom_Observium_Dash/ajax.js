// The AJAX function...

function AJAX(){
try{
xmlHttp=new XMLHttpRequest(); // Firefox, Opera 8.0+, Safari
return xmlHttp;
}
catch (e){
try{
xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
return xmlHttp;
}
catch (e){
try{
xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
return xmlHttp;
}
catch (e){
alert("Your browser does not support AJAX.");
return false;
}
}
}
}

// Timestamp for preventing IE caching the GET request (common function)

function fetch_unix_timestamp()
{
 return parseInt(new Date().getTime().toString().substring(0, 10))
}

////////////////////////////////
//
// Refreshing container1 DIV 
//
////////////////////////////////

function refresh_container1_div(){

// Customise those settings
var seconds = 60;
var divid = "container1";
var url = "devices.php";

// Create xmlHttp

var xmlHttp_two = AJAX();

// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

// The code...

xmlHttp_two.onreadystatechange=function(){
if(xmlHttp_two.readyState==4){
document.getElementById(divid).innerHTML=xmlHttp_two.responseText;
setTimeout('refresh_container1_div()',seconds*1000);
}
}
xmlHttp_two.open("GET",nocacheurl,true);
xmlHttp_two.send(null);
}

// Start the refreshing process

window.onload = function startrefresh(){
var seconds = 60;
setTimeout('refresh_container1_div()',seconds*1000);
}

////////////////////////////////
//
// Refreshing container2 DIV 
//
////////////////////////////////

function refresh_container2_div(){

// Customise those settings

var seconds = 60;
var divid = "container2";
var url = "porterror.php";

// Create xmlHttp

var xmlHttp_three = AJAX();

// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

// The code...

xmlHttp_three.onreadystatechange=function(){
if(xmlHttp_three.readyState==4){
document.getElementById(divid).innerHTML=xmlHttp_three.responseText;
setTimeout('refresh_container2_div()',seconds*1000);
}
}
xmlHttp_three.open("GET",nocacheurl,true);
xmlHttp_three.send(null);
}

// Start the refreshing process

window.onload = function startrefresh(){
var seconds = 60;
setTimeout('refresh_container2_div()',seconds*1000);
}

////////////////////////////////
//
// Refreshing container3 DIV 
//
////////////////////////////////

function refresh_container3_div(){

// Customise those settings

var seconds = 60;
var divid = "container3";
var url = "roomalert.php?";

// Create xmlHttp

var xmlHttp_four = AJAX();

// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

// The code...

xmlHttp_four.onreadystatechange=function(){
if(xmlHttp_four.readyState==4){
document.getElementById(divid).innerHTML=xmlHttp_four.responseText;
setTimeout('refresh_container3_div()',seconds*1000);
}
}
xmlHttp_four.open("GET",nocacheurl,true);
xmlHttp_four.send(null);
}

// Start the refreshing process

window.onload = function startrefresh(){
var seconds = 60;
setTimeout('refresh_container3_div()',seconds*1000);
}

