<?php
$host = "127.0.0.1";
$port = 12345;
$chunk_size = 1024;
$data_size = 1000 * 1024 * 1024; // 500 MB

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($sock, $host, $port);

$total_bytes = 0;
$total_messages = 0;
$start_time = microtime(true);

$data = str_repeat("A", $chunk_size);

while ($total_bytes < $data_size) {
    socket_write($sock, $data, strlen($data));

    // Așteptăm ACK de la server
    $ack = socket_read($sock, 3);
    if ($ack === "ACK") {
        $total_bytes += $chunk_size;
        $total_messages++;
    }
}

socket_close($sock);
$end_time = microtime(true);

echo "\n--- TCP Stop-and-Wait Transfer Complete ---\n";
echo "Protocol: TCP (Stop-and-Wait)\n";
echo "Total Messages Sent: $total_messages\n";
echo "Total Bytes Sent: $total_bytes\n";
echo "Total Time: " . round($end_time - $start_time, 2) . " sec\n";
?>
