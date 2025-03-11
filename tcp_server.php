<?php
$host = "127.0.0.1";
$port = 12345;
$buffer_size = 65535; 

// Creare socket TCP
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$sock) {
    die("Eroare la crearea socket-ului: " . socket_strerror(socket_last_error()) . "\n");
}

if (!socket_bind($sock, $host, $port)) {
    die("Eroare la bind: " . socket_strerror(socket_last_error($sock)) . "\n");
}

if (!socket_listen($sock)) {
    die("Eroare la listen: " . socket_strerror(socket_last_error($sock)) . "\n");
}

echo "TCP Server listening on port $port...\n";

$client = socket_accept($sock);
if (!$client) {
    die("Eroare la acceptare client: " . socket_strerror(socket_last_error($sock)) . "\n");
}

$total_bytes = 0;
$total_messages = 0;
$start_time = microtime(true);

while (($data = socket_read($client, $buffer_size)) !== false) {
    $total_bytes += strlen($data);
    $total_messages++;
}

$end_time = microtime(true);
socket_close($client);
socket_close($sock);

$elapsed_time = round($end_time - $start_time, 2);
$speed = $elapsed_time > 0 ? round($total_bytes / (1024 * 1024) / $elapsed_time, 2) : 0;

echo "\n--- TCP Transfer Complete ---\n";
echo "Protocol: TCP\n";
echo "Total Messages: $total_messages\n";
echo "Total Bytes: $total_bytes\n";
echo "Total Time: $elapsed_time sec\n";
echo "Transfer Speed: $speed MB/s\n";
?>