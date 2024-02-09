<?php
$host = 'localhost';
$username = 'root';
$password = 'Rostik2005$';
$database = 'library';
$dumpFile = 'library.sql';

shell_exec("mysqldump -h{$host} -u{$username} -p{$password} {$database} > {$dumpFile}");
