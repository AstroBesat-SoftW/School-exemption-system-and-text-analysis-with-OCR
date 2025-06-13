<?php
// JSON verisini al
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['tam_sonuc'])) {
    http_response_code(400);
    echo "Eksik veri.";
    exit;
}

$file = __DIR__ . '/sonuc_okulumuz.txt';

if (file_put_contents($file, $data['tam_sonuc']) !== false) {
    echo "Başarıyla kaydedildi.";
} else {
    http_response_code(500);
    echo "Dosyaya yazılamadı.";
}
?>
