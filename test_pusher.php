<?php
require 'vendor/autoload.php';

$pusher = new Pusher\Pusher(
    '29d0f6e67af01e5a436e',
    '0a01dcff4531d68357d1',
    '2087354',
    ['cluster' => 'ap1', 'useTLS' => false]
);

echo "Testing Pusher connection...\n";
try {
    $result = $pusher->trigger('test-channel', 'test-event', ['message' => 'hello']);
    echo "Pusher Response: " . json_encode($result) . "\n";
    if ($result) {
        echo "✓ Pusher connection SUCCESS\n";
    } else {
        echo "✗ Pusher trigger failed\n";
    }
} catch (Exception $e) {
    echo "✗ Pusher Error: " . $e->getMessage() . "\n";
}
