<?php

require_once "base.php";

function httpResponseOk($headers, $body = null)
{
    outputHttpResponse(
        HTTP_STATUS_OK,
        HTTP_STATUS_OK_MESSAGE,
        $headers,
        $body ?? strtolower(HTTP_STATUS_OK_MESSAGE)
    );
}

function httpResponseNotFound($headers, $body = null)
{
    outputHttpResponse(
        HTTP_STATUS_NOT_FOUND,
        HTTP_STATUS_NOT_FOUND_MESSAGE,
        $headers,
        $body ?? strtolower(HTTP_STATUS_NOT_FOUND_MESSAGE)
    );
}

function httpResponseBadRequest($headers, $body = null)
{
    outputHttpResponse(
        HTTP_STATUS_BAD_REQUEST,
        HTTP_STATUS_BAD_REQUEST_MESSAGE,
        $headers,
        $body ?? strtolower(HTTP_STATUS_BAD_REQUEST_MESSAGE)
    );
}

function httpResponseServerError($headers, $body = null)
{
    outputHttpResponse(
        HTTP_STATUS_SERVER_ERROR,
        HTTP_STATUS_SERVER_ERROR_MESSAGE,
        $headers,
        $body ?? strtolower(HTTP_STATUS_SERVER_ERROR_MESSAGE)
    );
}

function httpResponseUnauthorized($headers, $body = null)
{
    outputHttpResponse(
        HTTP_STATUS_UNAUTHORIZED,
        HTTP_STATUS_UNAUTHORIZED_MESSAGE,
        $headers,
        $body ?? strtolower(HTTP_STATUS_UNAUTHORIZED_MESSAGE)
    );
}