<?php
$host = "0.0.0.0";
$port = 12346;
$buffer_size = 65535; // 64 KB maxim

// Creare socket UDP
$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if (!$sock) {
    die("Eroare la crearea socket-ului: " . socket_strerror(socket_last_error()) . "\n");
}

// Optimizare buffer pentru a evita pierderea pachetelor
socket_set_option($sock, SOL_SOCKET, SO_RCVBUF, 10 * 1024 * 1024);
socket_set_option($sock, SOL_SOCKET, SO_SNDBUF, 10 * 1024 * 1024);

if (!socket_bind($sock, $host, $port)) {
    die("Eroare la bind: " . socket_strerror(socket_last_error($sock)) . "\n");
}

echo "UDP Server listening on port $port...\n";

$total_bytes = 0;
$total_messages = 0;
$start_time = microtime(true);

while ($total_bytes < 1000 * 1024 * 1024) {
    $read = [$sock];
    $write = null;
    $except = null;
    socket_select($read, $write, $except, 0, 100000); // Timeout mai mare pentru eficiență // 10 ms timeout

    $data = "";
    $bytes_received = @socket_recvfrom($sock, $data, $buffer_size, 0, $remote_ip, $remote_port);
    if ($bytes_received === false) {
        continue;
    }
    
    $total_bytes += $bytes_received;
    $total_messages++;
}

$end_time = microtime(true);
socket_close($sock);

$elapsed_time = round($end_time - $start_time, 2);
$speed = $elapsed_time > 0 ? round($total_bytes / (1024 * 1024) / $elapsed_time, 2) : 0;

echo "\n--- UDP Transfer Complete ---\n";
echo "Protocol: UDP\n";
echo "Total Messages: $total_messages\n";
echo "Total Bytes: $total_bytes\n";
echo "Total Time: $elapsed_time sec\n";
echo "Transfer Speed: $speed MB/s\n";
?>
