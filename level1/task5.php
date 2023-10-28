<?php

require_once "base.php";
require_once "responses.php";

const DEFAULT_FILE_NAME = "/index.html";
const ELSE_DIRECTORY = "else";

function getHost($headers)
{
    $host = "";

    foreach ($headers as $header) {
        if ($header[0] == "Host") {
            $host = $header[1];
        }
    }

    return $host;
}

function getBaseDirectory($headers)
{
    return match (getHost($headers)) {
        "student.shpp.me" => "student",
        "another.shpp.me" => "another",
        default => ELSE_DIRECTORY
    };
}

function getFilePath($uri, $baseDirectory)
{
    $uri = $uri == "/" ? DEFAULT_FILE_NAME : $uri;
    return $baseDirectory . $uri;
}

function processHttpRequest($method, $uri, $headers, $body)
{
    $baseDirectory = getBaseDirectory($headers);

    if ($baseDirectory == ELSE_DIRECTORY) {
        httpResponseNotFound($headers);
        return;
    }

    $filePath = getFilePath($uri, $baseDirectory);

    if (file_exists($filePath)) {
        httpResponseOk($headers, file_get_contents($filePath));
    } else {
        httpResponseNotFound($headers);
    }
}

$http = parseTcpStringAsHttpRequest($contents);
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);
