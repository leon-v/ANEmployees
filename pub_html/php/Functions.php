<?php

/**
 * Debug function for displaying variable values in a preformatted block.
 *
 * This function takes one or more values and displays them in a preformatted
 * block for easy debugging. It can handle various types of data, including
 * arrays and objects.
 *
 * @param mixed ...$values One or more values to be displayed.
 */
function debug(...$values){
    ?>
    <pre>
        <?php
        foreach ($values as $value) {
            if ($value === null) {
                echo 'NULL';
            }
            else{
                print_r($value);
            }

        }
        ?>
    </pre>
    <?php
}

/**
 * Send a JSON response to the client with optional HTTP status code.
 *
 * This function sends a JSON response to the client with the specified data
 * and an optional HTTP status code. It sets the "Content-Type" header to
 * "application/json" and includes proper HTTP response code.
 *
 * @param mixed $data The data to be converted to JSON and sent to the client.
 * @param int $code (Optional) The HTTP status code to send (default is 200 OK).
 */
function jsonResponse($data, $code = 200) {

    // Set the Content-Type header to indicate JSON data.
    header("Content-Type: application/json");

    // Set the HTTP response code.
    http_response_code($code);

    // Encode the data as JSON and send it to the client.
    echo json_encode($data, JSON_PRETTY_PRINT);

    // Terminate the script to prevent further output.
    exit;
}
