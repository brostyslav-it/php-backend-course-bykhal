<?php

define("HTTP_STATUS_OK", 200);
define("HTTP_STATUS_OK_MESSAGE", "OK");

define("HTTP_STATUS_BAD_REQUEST", 400);
define("HTTP_STATUS_BAD_REQUEST_MESSAGE", "Bad Request");

define("HTTP_STATUS_NOT_FOUND", 404);
define("HTTP_STATUS_NOT_FOUND_MESSAGE", "Not Found");

define("HTTP_STATUS_SERVER_ERROR", 500);
define("HTTP_STATUS_SERVER_ERROR_MESSAGE", "Internal Server Error");

define("HTTP_STATUS_UNAUTHORIZED", 401);
define("HTTP_STATUS_UNAUTHORIZED_MESSAGE", "Unauthorized");

function readHttpLikeInput() {
    $f = fopen('php://stdin', 'r');

    $store = "";
    $toread = 0;

    while( $line = fgets( $f ) ) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/',$line,$m))
            $toread=$m[1]*1;
        if ($line == "\r\n")
            break;
    }

    if ($toread > 0)
        $store .= fread($f, $toread);

    return $store;
}

$contents = readHttpLikeInput();

function parseHeaders($lines)
{
    $headers = [];

    for ($i = 1; $i < count($lines) and str_contains($lines[$i], ":"); $i++) {
        $headers[] = explode(": ", $lines[$i]);
    }

    return $headers;
}
function parseTcpStringAsHttpRequest($string) {
    $lines = explode("\n", $string);
    $requestParams = explode(" ", $lines[0]);
    $headers = [];
    $body = "";

    if (count($lines) > 1) {
        $headers = parseHeaders($lines);
        $body = trim($lines[count($lines) - 1]);
    }

    return array(
        "method" => $requestParams[0],
        "uri" => $requestParams[1],
        "headers" => $headers,
        "body" => $body,
    );
}

function outputHttpResponse($statuscode, $statusmessage, $headers, $body)
{
    $response = "HTTP/1.1 $statuscode $statusmessage" . PHP_EOL;

    $response .= "Date: " . gmdate("r") . PHP_EOL;
    $response .= "Server: Apache/2.2.14 (Win32)" . PHP_EOL;
    $response .= "Connection: Closed" . PHP_EOL;
    $response .= "Content-Type: text/html; charset=utf-8" . PHP_EOL;
    $response .= "Content-Length: " . strlen($body) . PHP_EOL;
    $response .= PHP_EOL . "$body";

    echo $response;
}
