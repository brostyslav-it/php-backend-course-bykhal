<?php

require_once "base.php";

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));
