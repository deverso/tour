<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$conn = new AMQPStreamConnection(getenv('RABBITMQ_HOST'), getenv('RABBITMQ_PORT'), getenv('RABBITMQ_USER'), getenv('RABBITMQ_PASSWORD'), getenv('RABBITMQ_VHOST'));
$channel = $conn->channel();

$channel->queue_declare('importer', false, true, false, false);

$body = [
	"title"=> "Trips",
	"destinations"=> "Germany, Czech Republic, Slovakia, Hungary, Austria",
	"age"=> "5 to 99",
	"travel_style"=> "group, fully guided, easy, explorer, coach/bus",
	"operated_lang"=> "english",
	"operator"=> "Travel Safe",
	"operator_badge"=> "gold",
    "cover_image" => "https://i.imgur.com/zY5pvHe.jpeg"
];

$msg = new AMQPMessage(
    json_encode($body),
    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

$channel->basic_publish($msg, '', 'importer');

echo " [x] Message added in the queue...'\n";

$channel->close();
$conn->close();