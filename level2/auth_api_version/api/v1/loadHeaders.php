<?php
header("Access-Control-Allow-Origin: https://frontend.com");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json");

session_set_cookie_params([
    'samesite' => 'None',
    'secure' => true,
]);
