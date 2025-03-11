<?php
$host = "0.0.0.0";
$port = 12345;
$buffer_size = 65535;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $host, $port);
socket_listen($sock);

echo "TCP Server (Stop-and-Wait) listening on port $port...\n";

$client = socket_accept($sock);

$total_bytes = 0;
$total_messages = 0;
$start_time = microtime(true);

while (true) {
    $data = socket_read($client, $buffer_size);
    if (!$data) break;

    $total_bytes += strlen($data);
    $total_messages++;

    // Trimite ACK către client
    socket_write($client, "ACK");
}

$end_time = microtime(true);
socket_close($client);
socket_close($sock);

echo "\n--- TCP Stop-and-Wait Transfer Complete ---\n";
echo "Protocol: TCP (Stop-and-Wait)\n";
echo "Total Messages: $total_messages\n";
echo "Total Bytes: $total_bytes\n";
echo "Total Time: " . round($end_time - $start_time, 2) . " sec\n";
?>
