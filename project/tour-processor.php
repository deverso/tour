<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$conn = new AMQPStreamConnection(getenv('RABBITMQ_HOST'), getenv('RABBITMQ_PORT'), getenv('RABBITMQ_USER'), getenv('RABBITMQ_PASSWORD'), getenv('RABBITMQ_VHOST'));
$channel = $conn->channel();

$channel->queue_declare('importer', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

function sendToBucket ($img) {
    // Send images to bucket returning the query string for the image
    return 'zY5pvHe.jpeg';
}

function saveDB($query) {
    $db = new SQLite3('tour-radar.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $db->query($query);
    $db->close();
}

$callback = function ($msg) {
    $data = json_decode($msg->body, true);

    $imgPath = sendToBucket($data['cover_image']);

    $dbQuery = "INSERT INTO operators VALUES (NULL,
        '" . $data['title'] . "',
        '" . $data['destinations'] . "',
        '" . $data['age'] . "',
        '" . $data['travel_style'] . "',
        '" . $data['operated_lang'] . "',
        '" . $data['operator'] . "',
        '" . $data['operator_badge'] . "',
        '" . $imgPath . "'
    );";
    saveDB($dbQuery);


    echo ' [x] Data from ', $data['operator'], " has been processed! \n";
};

$channel->basic_consume('importer', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$conn->close();
