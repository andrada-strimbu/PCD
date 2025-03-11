<?php

// Reduce errors
error_reporting(~E_WARNING);

// Server IP and port
$server = '127.0.0.1';
$port = 9999;

// Create a UDP socket
if (!($sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}

echo "UDP Client: Socket created\n";

// Message size (in bytes)
$message_size = 512; // 512 bytes per chunk (you can increase this for testing larger message sizes)
$data_to_send = 500 * 1024 * 1024; // 500 MB

// Generate the data to send (simulate large file transfer)
$data = str_repeat("A", $message_size); // 512 bytes per message

// Start the timer
$start_time = microtime(true);

// Track data sent and received
$total_data_sent = 0;
$total_data_received = 0;
$total_messages_sent = 0;

// Send the data in chunks
while ($total_data_sent < $data_to_send) {
    socket_sendto($sock, $data, $message_size, 0, $server, $port);
    $total_data_sent += $message_size;
    $total_messages_sent++;

}

// Receive the echoed data from the server (optional)
while ($total_data_received < $total_data_sent) {
    $response = '';
    socket_recvfrom($sock, $response, $message_size, 0, $server, $port);
    $total_data_received += strlen($response);
    if ($total_data_received % 1024 * 1024 == 0) {
        echo "Received $total_data_received bytes so far.\n";
    }
}

// End the timer
$end_time = microtime(true);
$elapsed_time = $end_time - $start_time;

// Calculate transfer speed (in MB/s)
$transfer_speed = ($total_data_sent / 1024 / 1024) / $elapsed_time; // in MB/s

// Print summary
echo "\n--- UDP Transfer Complete ---\n";
echo "Protocol: UDP\n";
echo "Total Messages Sent: $total_messages_sent\n";
echo "Total Bytes Sent: $total_data_sent\n";
echo "Total Time: " . number_format($elapsed_time, 2) . " sec\n";
echo "Transfer Speed: " . number_format($transfer_speed, 2) . " MB/s\n";

// Calculate data loss (if any)
$data_loss = $total_data_sent - $total_data_received;
echo "Data Loss: $data_loss bytes\n";

socket_close($sock);

?>
