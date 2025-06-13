<?php
// Hata günlüklerinin log.txt dosyasına yazılması
ini_set('log_errors', 1);
ini_set('error_log', 'log.txt');  // Hata günlüklerini log.txt'ye yazdır

header('Content-Type: application/json'); // JSON yanıt döneceğimizi belirtelim

$upload_dir = 'ogrenciler/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        include "db.php";

        if (!$conn) {
            throw new Exception("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
        }

        // Kullanıcı bilgilerini al
        $isim = $_POST['isim'] ?? 'Sahte İsim';
        $soyisim = $_POST['soyisim'] ?? 'Sahte Soyisim';
        $tc = $_POST['tc'] ?? '00000000000';
        $telefon = $_POST['telefon'] ?? '0000000000';
        $kayit_tarihi = $_POST['kayit_tarihi'] ?? '2025-01-01';
        $geldigi_okul = $_POST['gel_okul'] ?? 'Sahte Okul';
        $geldigi_bolum = $_POST['gel_bolum'] ?? 'Sahte Bölüm';

        // Geldiği okulun kontrolü
        if ($geldigi_okul !== 'Sahte Okul') {
            $query2 = "SELECT universite_ad FROM universiteler WHERE id = ?";
            if ($stmt2 = $conn->prepare($query2)) {
                $stmt2->bind_param("i", $geldigi_okul);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if ($row = $result2->fetch_assoc()) {
                    $geldigi_okul = $row['universite_ad'];
                } else {
                    $geldigi_okul = 'Bilinmeyen Üniversite';
                }
                $stmt2->close();
            } else {
                error_log("Veritabanı sorgusu hatası: " . $conn->error);
            }
        }

        // Geldiği bölümün kontrolü
        if ($geldigi_bolum !== 'Sahte Bölüm') {
            $query3 = "SELECT bolum_ad FROM bolumler WHERE id = ?";
            if ($stmt3 = $conn->prepare($query3)) {
                $stmt3->bind_param("i", $geldigi_bolum);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                if ($row = $result3->fetch_assoc()) {
                    $geldigi_bolum = $row['bolum_ad'];
                } else {
                    $geldigi_bolum = 'Bilinmeyen Bölüm';
                }
                $stmt3->close();
            } else {
                error_log("Veritabanı sorgusu hatası: " . $conn->error);
            }
        }

        $gittigi_okul = $_POST['git_okul'] ?? 'Sahte Git Okul';
        $gittigi_bolum = $_POST['git_bolum'] ?? 'Sahte Git Bölüm';

        // Görsel verisini al
        if (isset($_POST['gorsel_data'])) {
            $gorsel_data = $_POST['gorsel_data'];
            $gorsel_data = str_replace('data:image/png;base64,', '', $gorsel_data);
            $gorsel_data = str_replace(' ', '+', $gorsel_data);
            $gorsel_binary = base64_decode($gorsel_data);
            $gorsel_path = $upload_dir . $tc . '_tablo_gorsel.png';
            if (!file_put_contents($gorsel_path, $gorsel_binary)) {
                throw new Exception("Tablo görseli kaydedilemedi.");
            }
        } else {
            throw new Exception("Tablo görseli eksik.");
        }

        // Dosyaları kaydet
        if (isset($_FILES['transkript_pdf'])) {
            $transkript_path = $upload_dir . $tc . '_transkript.pdf';
            if (!move_uploaded_file($_FILES['transkript_pdf']['tmp_name'], $transkript_path)) {
                throw new Exception("Transkript dosyası yüklenemedi.");
            }
        } else {
            throw new Exception("Transkript dosyası eksik.");
        }

        if (isset($_FILES['icerikler_pdf'])) {
            $icerikler_path = $upload_dir . $tc . '_icerikler.pdf';
            if (!move_uploaded_file($_FILES['icerikler_pdf']['tmp_name'], $icerikler_path)) {
                throw new Exception("Ders içerikleri dosyası yüklenemedi.");
            }
        } else {
            throw new Exception("Ders içerikleri dosyası eksik.");
        }

        // Dosya adlarını ayrı değişkenlere ata (bind_param için)
        $gorsel_basename = basename($gorsel_path);
        $transkript_basename = basename($transkript_path);
        $icerikler_basename = basename($icerikler_path);

        // Veritabanına kayıt ekle
        $stmt = $conn->prepare("
            INSERT INTO muaf_ogrenci (
                isim, soyisim, tc, telefon, geldigi_okul, geldigi_bolum,
                gittigi_okul, gittigi_bolum, tarih, muaf_gorsel, transkript, ders_icerigi
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new Exception("Veritabanı sorgusu hazırlanamadı: " . $conn->error);
        }

        $stmt->bind_param(
            "ssssssssssss",
            $isim, $soyisim, $tc, $telefon, $geldigi_okul, $geldigi_bolum,
            $gittigi_okul, $gittigi_bolum, $kayit_tarihi,
            $gorsel_basename, $transkript_basename, $icerikler_basename
        );

        if (!$stmt->execute()) {
            throw new Exception("Veritabanı kaydı başarısız: " . $stmt->error);
        }

        $response['message'] = "Dosyalar ve veritabanı kaydı başarıyla tamamlandı!";
        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        error_log("Hata: " . $e->getMessage());
        http_response_code(400);
        $response['error'] = $e->getMessage();
    }
}

echo json_encode($response);
?>
