<?php
/**
 * Created by PhpStorm.
 * User: Tobi
 * Date: 30/11/2016
 * Time: 16:46
 */


header('Content-type: application/json');
header("Access-Control-Allow-Method: *");

// WARNING: Access-Control-Allow-Origin: * is dangerous
// Do not use this line on public servers.
header("Access-Control-Allow-Origin: *");


error_reporting(E_ERROR);


if (isset($_GET['url']) && !empty($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $url = 'http://feeds.feedburner.com/Techcrunch/europe';
}

$failureObj = array(
    'status' => 'error',
    'code' => 500,
    'message' => 'Invalid url. The passed URL ' . $url . ' could not be loaded. ',
    'reasons' => array('The page is not formatted with valid XML', 'The page is not available', 'The URL is invalid')
);

try {
    $xml = simplexml_load_file($url);
    $jsonFromXML = json_encode($xml);

    // if json_encode fails, it - for some reason - returns 'false' as a String.
    if ($jsonFromXML != 'false') {
        echo $jsonFromXML;
    } else {
        echo json_encode($failureObj);
    }

} catch (Exception $e) {
    json_encode($failureObj);
    exit();
}

