<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dosya Görüntüleyici</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            height: 80vh;
        }
        .panel {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            border-right: 1px solid #ccc;
        }
        .panel:last-child {
            border-right: none;
        }
        h2 {
            text-align: center;
        }
        pre {
            background: #f5f5f5;
            padding: 10px;
            white-space: pre-wrap;
            word-wrap: break-word;
            border-radius: 8px;
        }
        .result-box {
            padding: 10px;
            border-top: 1px solid #ccc;
        }
        textarea {
            width: 100%;
            height: 150px;
            font-family: monospace;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sol Panel -->
        <div class="panel" id="panel-left">
            <h2>sonuc.txt</h2>
            <pre id="left-content">
<?php
    $file = 'sonuc.txt';
    echo file_exists($file) ? htmlspecialchars(file_get_contents($file)) : 'Dosya bulunamadı.';
?>
            </pre>
        </div>

        <!-- Sağ Panel -->
        <div class="panel" id="panel-right">
            <h2>sonuc_okulumuz.txt</h2>
            <pre id="right-content">
<?php
    $file2 = 'sonuc_okulumuz.txt';
    echo file_exists($file2) ? htmlspecialchars(file_get_contents($file2)) : 'Dosya bulunamadı.';
?>
            </pre>
        </div>
    </div>

    <!-- Alt Metin Kutusu -->
    <div class="result-box">
        <h3>Eşleşen Satırlar:</h3>
        <textarea id="output" readonly></textarea>
    </div>

    <!-- JavaScript -->
<script>
window.addEventListener("DOMContentLoaded", () => {
    const leftText = document.getElementById("left-content").innerText;
    const rightText = document.getElementById("right-content").innerText;
    const output = document.getElementById("output");

    const leftLines = leftText.split('\n');
    const rightLines = rightText.split('\n');

    const sonucSatirlari = [];

    // Sağ dosyadaki her satırı sırayla işle
    for (let i = 1; i < rightLines.length; i++) { // 0. satır başlıksa atla
        const satir = rightLines[i].trim();
        if (!satir) continue;

        const kolonlar = satir.split('\t');
        if (kolonlar.length < 2) continue;

        const dersAdi = kolonlar[1].trim().toLowerCase();

        // Sol dosyadaki satırlarda bu ders adı geçiyor mu?
        for (const solSatir of leftLines) {
            if (solSatir.toLowerCase().includes(dersAdi)) {
                // Eşleşme bulundu, sol ve sağ satırı yan yana yaz
                sonucSatirlari.push(`${solSatir.trim()}  <--->  ${satir}`);
                break; // Aynı ders için bir eşleşme yeterli
            }
        }
    }

    output.value = sonucSatirlari.length
        ? sonucSatirlari.join('\n')
        : "Eşleşme bulunamadı.";
});
</script>




</body>
</html>
