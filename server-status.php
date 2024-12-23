<?php
if (!isset($_GET['ip']) || !isset($_GET['port'])) {
    echo json_encode(['error' => 'Missing IP or Port']);
    exit;
}

$ip = $_GET['ip'];
$port = intval($_GET['port']);

// الاتصال بالسيرفر باستخدام Sockets
$socket = @fsockopen($ip, $port, $errno, $errstr, 1);
if (!$socket) {
    echo json_encode(['error' => 'Server is offline']);
    exit;
}

// طلب بيانات السيرفر
fwrite($socket, "\xfe\x01");
$data = fread($socket, 1024);
fclose($socket);

if ($data === false || substr($data, 0, 1) != "\xff") {
    echo json_encode(['error' => 'Invalid server response']);
    exit;
}

$data = substr($data, 3);
$data = iconv('UTF-16BE', 'UTF-8', $data);
$data = explode("\x00", $data);

if (count($data) < 6) {
    echo json_encode(['error' => 'Incomplete server data']);
    exit;
}

$response = [
    'name' => $data[1],
    'onlinePlayers' => intval($data[4]),
    'maxPlayers' => intval($data[5]),
    'players' => [] // اللاعبين غير متاحين في هذا الإصدار.
];

echo json_encode($response);
