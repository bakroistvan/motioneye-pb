<?php
require __DIR__ . '/vendor/autoload.php';

use \Curl\Curl;


$base_dir = "/var/lib/motioneye/";
$token    = "xxxxxxxx put your api token here xxxxxxxxx";

$image_path = $base_dir . $argv[1] . '.jpg';



$curl = new Curl();
$curl->setJsonDecoder(function($x) {return json_decode($x, true);});

$curl->setHeader('Access-Token', $token);
$curl->setHeader('Content-Type', 'application/json');



$post = [
	'file_name' => $argv[1] . '.jpg',
	'file_type' => 'image/jpeg'
];
$curl->post('https://api.pushbullet.com/v2/upload-request', json_encode($post));
var_dump($curl->response);

$up_req = $curl->response;


$curl->setHeader('Content-Type', 'multipart/form-data');
$curl->post($up_req['upload_url'], [
	'file' => '@' . $image_path
]);
var_dump($curl->response);

$curl->setHeader('Content-Type', 'application/json');
$post = [
	'type' => 'file',
	'file_name' => $argv[1] . '.jpg',
	'file_url' => $up_req['file_url'],
	'file_type' => 'image/jpeg',
	'body' => 'Home motion detected'

];
$curl->post('https://api.pushbullet.com/v2/pushes', json_encode($post));
var_dump($curl->response);

#curl --header 'Access-Token: $token' \
#	--header 'Content-Type: application/json' \
#	--data-binary '{"body":"%Y-%m-%d/%H-%M-%S-%q","title":"Home motion","type":"note"}' \
#	--request POST \
#	https://api.pushbullet.com/v2/pushes
