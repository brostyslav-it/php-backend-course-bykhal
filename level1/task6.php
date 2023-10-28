<?php
const VISITS_INCREASE_VALUE = 1;
const FILE_NAME = "counter.txt";

if (!file_exists(FILE_NAME)) {
    file_put_contents(FILE_NAME, 0);
}

echo file_get_contents(FILE_NAME);

file_put_contents(FILE_NAME, intval(file_get_contents(FILE_NAME)) + VISITS_INCREASE_VALUE);
