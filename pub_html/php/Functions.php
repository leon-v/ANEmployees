<?php

function debug(...$values){
    ?>
    <pre>
        <?php
        foreach ($values as $value) {
            print_r($value);
        }
        ?>
    </pre>
    <?php
}

function jsonResponse($data, $code = 200) {

    header("Content-Type: application/json");
    http_response_code($code);
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}
