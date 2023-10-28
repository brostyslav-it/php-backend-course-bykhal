<?php

require_once "base.php";
require_once "responses.php";

const BASE_SUM_URL = "/sum";
const REQUEST_METHOD = "GET";
const NUMS_URL_PART = "?nums=";

function parseNumbers($uri)
{
    return explode(",", explode("=", $uri)[1]);
}

function getNumbersSum($uri)
{
    $numbers = parseNumbers($uri);
    $sum = 0;

    foreach ($numbers as $number) {
        $sum += +$number;
    }

    return $sum;
}

function processHttpRequest($method, $uri, $headers, $body)
{
    if (!str_starts_with($uri, BASE_SUM_URL)) {
        httpResponseNotFound($headers);
        return;
    }

    if ($method != REQUEST_METHOD or !str_contains($uri, NUMS_URL_PART)) {
        httpResponseBadRequest($headers);
        return;
    }

    httpResponseOk($headers, strval(getNumbersSum($uri)));
}

$http = parseTcpStringAsHttpRequest($contents);
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);
