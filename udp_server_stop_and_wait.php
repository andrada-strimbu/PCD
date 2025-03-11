<?php
$host = "0.0.0.0";
$port = 12346;
$buffer_size = 65535;

$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($sock, $host, $port);

echo "UDP Server (Stop-and-Wait) listening on port $port...\n";

$total_bytes = 0;
$total_messages = 0;
$start_time = microtime(true);

while (true) {
    $data = "";
    socket_recvfrom($sock, $data, $buffer_size, 0, $remote_ip, $remote_port);
    if (!$data) break;

    $total_bytes += strlen($data);
    $total_messages++;

    // Trimite ACK înapoi la client
    socket_sendto($sock, "ACK", 3, 0, $remote_ip, $remote_port);
}

$end_time = microtime(true);
socket_close($sock);

echo "\n--- UDP Stop-and-Wait Transfer Complete ---\n";
echo "Protocol: UDP (Stop-and-Wait)\n";
echo "Total Messages: $total_messages\n";
echo "Total Bytes: $total_bytes\n";
echo "Total Time: " . round($end_time - $start_time, 2) . " sec\n";
?>
