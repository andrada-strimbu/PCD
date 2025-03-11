<?php
$host = "127.0.0.1";
$port = 12345;
$chunk_size = 1024; // 1 KB
$data_size = 1000 * 1024 * 1024; // 500 MB

// Creare socket TCP
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$sock) {
    die("Eroare la crearea socket-ului: " . socket_strerror(socket_last_error()) . "\n");
}

if (!socket_connect($sock, $host, $port)) {
    die("Eroare la conectare: " . socket_strerror(socket_last_error($sock)) . "\n");
}

$total_bytes = 0;
$total_messages = 0;
$start_time = microtime(true);

$data = str_repeat("A", $chunk_size); // Bloc de date

while ($total_bytes < $data_size) {
    $sent = socket_write($sock, $data, $chunk_size);
    if ($sent === false) {
        echo "Eroare la trimitere: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }
    $total_bytes += $sent;
    $total_messages++;
}

socket_close($sock);
$end_time = microtime(true);

$elapsed_time = round($end_time - $start_time, 2);
$speed = $elapsed_time > 0 ? round($total_bytes / (1024 * 1024) / $elapsed_time, 2) : 0;

echo "\n--- TCP Transfer Complete ---\n";
echo "Protocol: TCP\n";
echo "Total Messages Sent: $total_messages\n";
echo "Total Bytes Sent: $total_bytes\n";
echo "Total Time: $elapsed_time sec\n";
echo "Transfer Speed: $speed MB/s\n";
?>