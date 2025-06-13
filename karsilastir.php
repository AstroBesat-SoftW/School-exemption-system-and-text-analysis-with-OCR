<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ders Eşleşme Kontrolü</title>
   <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f2f5;
        color: #333;
    }

    h2, h3 {
        margin: 0 0 10px;
        font-weight: 600;
    }

    .container {
        display: flex;
        gap: 20px;
        padding: 30px;
        height: 80vh;
    }

    .panel {
        flex: 1;
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    #left-content{
        font-size: 10px;
    }

    pre {
        background: #f7f9fc;
        padding: 15px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        font-size: 0.95rem;
        white-space: pre-wrap;
        word-wrap: break-word;
        flex: 1;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
    }

    .result-box {
        margin: 0 30px 30px;
        padding: 20px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-top: 4px solid #4a90e2;
    }

    #output {
        margin-top: 10px;
        line-height: 1.6;
        font-family: monospace;
        white-space: pre-wrap;
    }

    .green {
        color: #27ae60;
        font-weight: bold;
    }

    .red {
        color: #e74c3c;
        font-weight: bold;
    }

    .gray {
        color: #7f8c8d;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            height: auto;
        }

        .result-box {
            margin: 20px;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <!-- Sol Panel -->
        <div class="panel" id="panel-left">
            <h2>sonuc.txt</h2>
            <pre id="left-content"><?php
                $file = 'sonuc.txt';
                echo file_exists($file) ? htmlspecialchars(file_get_contents($file)) : 'Dosya bulunamadı.';
            ?></pre>
        </div>

        <!-- Sağ Panel -->
        <div class="panel" id="panel-right">
            <h2>sonuc_okulumuz.txt</h2>
            <pre id="right-content"><?php
                $file2 = 'sonuc_okulumuz.txt';
                echo file_exists($file2) ? htmlspecialchars(file_get_contents($file2)) : 'Dosya bulunamadı.';
            ?></pre>
        </div>
    </div>

    <!-- Alt Kısım -->
    <div class="result-box">
        <h3>Eşleşen Satırlar:</h3>
        <div id="output"></div>
    </div>

    <script>
    window.addEventListener("DOMContentLoaded", () => {
        const leftText = document.getElementById("left-content").innerText;
        const rightText = document.getElementById("right-content").innerText;
        const output = document.getElementById("output");

        const leftLines = leftText.split('\n');
        const rightLines = rightText.split('\n');

        const gecerliNotlar = ["aa", "ba", "bb", "cb", "cc"];
        const eslesenSonuclar = [];

        for (let i = 1; i < rightLines.length; i++) {
            const sagSatir = rightLines[i].trim();
            if (!sagSatir) continue;

            const kolonlar = sagSatir.split('\t');
            if (kolonlar.length < 2) continue;

            const dersAdi = kolonlar[1].trim().toLowerCase();
            const sagAKTS = parseInt(kolonlar[2]);

            for (const solSatir of leftLines) {
                const temizSol = solSatir.toLowerCase();
                if (temizSol.includes(dersAdi)) {
                    let renkClass = "gray";

                    // Harf notu bul
                    const harfNotuMatch = temizSol.match(/\b(aa|ba|bb|cb|cc|dd|fd|ff)\b/);
                    const harfNotu = harfNotuMatch ? harfNotuMatch[1] : "";
                    const notRiski = harfNotu && !gecerliNotlar.includes(harfNotu);

                    // Sol AKTS bul (örnek: "| 3 | 5 | BB |", 2. sayı alınır)
                    const solAKTSMatch = solSatir.match(/\|\s*(\d{1,2})\s*\|\s*(\d{1,2})\s*\|/);
                    const solAKTS = solAKTSMatch ? parseInt(solAKTSMatch[2]) : null;

                    const aktsRiski = (!isNaN(sagAKTS) && !isNaN(solAKTS) && sagAKTS > solAKTS);

                    // Renk belirle
                    if (notRiski || aktsRiski) {
                        renkClass = "red";
                    } else if (!notRiski && !aktsRiski && harfNotu) {
                        renkClass = "green";
                    }

                    eslesenSonuclar.push(
                        `<div class="${renkClass}">${solSatir.trim()}  <--->  ${sagSatir}</div>`
                    );
                    break;
                }
            }
        }

        output.innerHTML = eslesenSonuclar.length
            ? eslesenSonuclar.join('\n')
            : "<div>🛑 Eşleşme bulunamadı.</div>";
    });
    </script>
</body>
</html>
