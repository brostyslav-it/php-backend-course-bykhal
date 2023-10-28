<?php

require_once "base.php";
require_once "responses.php";

const SUCCESS_RETURN_VALUE = '<h1 style="color:green">FOUND</h1>';
const USERS_CREDENTIALS = "./files/passwords.txt";
const CREDENTIALS_SEPARATOR = ":";
const AUTH_URL = "/api/checkLoginAndPassword";
const REQUIRED_CONTENT_TYPE = "application/x-www-form-urlencoded";

function getContentType($headers)
{
    $contentType = "";

    foreach ($headers as $header) {
        if ($header[0] == "Content-Type") {
            $contentType = $header[1];
        }
    }

    return $contentType;
}

function getAuthCredentials($body)
{
    $parts = explode("&", $body);
    return [explode("=", $parts[0])[1], explode("=", $parts[1])[1]];
}

function isUserMatches($body)
{
    [$login, $password] = getAuthCredentials($body);
    $usersData = explode("\n", file_get_contents(USERS_CREDENTIALS));

    foreach ($usersData as $userData) {
        $parts = explode(CREDENTIALS_SEPARATOR, $userData);

        if ($login == $parts[0] and $password == $parts[1]) {
            return true;
        }
    }

    return false;
}

function processHttpRequest($method, $uri, $headers, $body)
{
    if ($uri != AUTH_URL) {
        httpResponseNotFound($headers);
        return;
    }

    if (getContentType($headers) != REQUIRED_CONTENT_TYPE) {
        httpResponseBadRequest($headers);
        return;
    }

    if (!file_exists(USERS_CREDENTIALS)) {
        httpResponseServerError($headers);
        return;
    }

    if (isUserMatches($body)) {
        httpResponseOk($headers, SUCCESS_RETURN_VALUE);
    } else {
        httpResponseUnauthorized($headers);
    }
}

$http = parseTcpStringAsHttpRequest($contents);
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);
