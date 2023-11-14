<?php
const ITEMS_FILE_PATH = "items.json";

function readFileData($filePath = ITEMS_FILE_PATH): string
{
    $fileData = "";
    $file = fopen($filePath, "r");

    if (flock($file, LOCK_SH)) {
        while (!feof($file)) {
            $fileData .= fgets($file);
        }

        flock($file, LOCK_UN);
    }

    fclose($file);

    return $fileData;
}

function writeToFile($data, $filePath = ITEMS_FILE_PATH): void
{
    $file = fopen($filePath, "w");

    if (flock($file, LOCK_EX)) {
        fwrite($file, $data);
        flock($file, LOCK_UN);
    }

    fclose($file);
}
