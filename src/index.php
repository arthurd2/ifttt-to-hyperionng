<?php
ini_set('error_reporting', -1);
ini_set('log_errors',1);
ini_set('display_errors',1);

$url = "http://$_ENV[HYPERION_IP]:$_ENV[HYPERION_PORT]/json-rpc";

$value 	  = strtolower(str_replace(' ','',$_GET['value']));
$function = strtolower(str_replace(' ','',$_GET['function']));

echo $function;

$arr = Array(
    //commands - first column is what you have to say or put in the url.

    "black" => "--clearall", //turns off all the solid light, also the set effects
    "off" => "--clearall", //turns off all the solid light, also the set effects
    "on" => "-p 150 -c peru", //nice warm static back light
    "0" => "--clearall", //turns off all the solid light, also the set effects
    "usb on" => "-E V4L", //turns on usb hdmi capture
    "usb off" => "-D V4L", //turns off usb hdmi capture
    "usb" => "-E V4L",  //turns on usb hdmi capture
    "standby" => "-D V4L", //turns off usb hdmi capture
    );


$colors['black'] = '[0,0,0]';
$colors['white'] = '[255,255,255]';
$colors['green'] = '[0,255,0]';
$colors['red']   = '[255,0,0]';
$colors['blue']  = '[0,0,255]';

// Commands from - https://docs.hyperion-project.org/en/json/Control.html
$command['color']       = '{"command":"color","color": %s, "duration":0, "priority":20,"origin":"IFTTT"}';
$command['adjustments'] = '{"command":"adjustment","adjustment":{"%s": %s}}';
$command['effect']      = '{"command":"effect", "effect":{"name":"%s"}, "duration":%s, "priority":20, "origin":"IFTTT"}';
$command['components']  = '{"command": "componentstate","componentstate": { "component": "%s","state": %s }}';

switch ($function){
    case 'enable':
        $command = sprintf($command['components'], strtoupper(str_replace(' ','',$value)), 'true');
        break;
    case 'disable':
        $command = sprintf($command['components'], strtoupper(str_replace(' ','',$value)), 'false');
        break;
    case 'effect':
		$command = sprintf($command['effect'], $_GET['value'], 0);
		break;
    case 'color':
		$command = sprintf($command['color'], $colors[$value]);
		break;
    case 'brightness':
		$command = sprintf($command['adjustments'],$function, $value );
		break;
    default:
        echo 'Function not found';
		die;
}
echo send_command($url,$command);


function send_command($url,$json){
    echo $json;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    $return = curl_exec($ch);
    return $return;
}