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

    const normalize = (text) => text
        .toLowerCase()
        .replace(/ğ/g, 'g').replace(/ü/g, 'u').replace(/ş/g, 's')
        .replace(/ı/g, 'i').replace(/ö/g, 'o').replace(/ç/g, 'c')
        .replace(/[^\w\s]/g, '') // noktalama temizle
        .replace(/\s+/g, ' ') // fazla boşlukları sadeleştir
        .trim();

    for (let i = 1; i < rightLines.length; i++) {
        const sagSatir = rightLines[i].trim();
        if (!sagSatir) continue;

        const kolonlar = sagSatir.split('\t');
        if (kolonlar.length < 3) continue;

        const dersAdiSag = normalize(kolonlar[1]);
        const sagAKTS = parseInt(kolonlar[2]);

        for (const solSatir of leftLines) {
            if (!solSatir.includes('|')) continue;

            let dersAdiSol = "";
            let harfNotu = "";
            let solAKTS = NaN;

            const parcalar = solSatir.split('|').map(p => p.trim()).filter(p => p.length > 0);

            // Ders adını çözümle
            let dersAdParcasi = "";

            // Format 1-2-4: "KOD - ADI" veya "KOD: ADI"
            const dersAdMatch = parcalar.find(p => /[-:]/.test(p));
            if (dersAdMatch) {
                const ayir = dersAdMatch.split(/[-:]/);
                dersAdParcasi = ayir.length > 1 ? ayir[1] : "";
            }
            // Format 3: "| KOD | ADI |"
            else if (parcalar.length >= 4) {
                dersAdParcasi = parcalar[1]; // İkinci parça genelde ders adı
            }

            dersAdiSol = normalize(dersAdParcasi);

            // Harf notu ve AKTS'yi bul
            const harfNotRegex = /\b(aa|ba|bb|cb|cc|dd|fd|ff)\b/;
            const notKandidatlari = parcalar.filter(p => harfNotRegex.test(p.toLowerCase()));

            if (notKandidatlari.length > 0) {
                harfNotu = notKandidatlari[0].toLowerCase();
            }

            // AKTS adayları: sayı olanlar
            const sayiParcalar = parcalar.map(p => parseInt(p)).filter(n => !isNaN(n));
            if (sayiParcalar.length > 0) {
                // AKTS'nin harf notundan sonra mı önce mi olduğunu anlamak zor olabilir, ama
                // genelde 1-15 arasında olur, o yüzden:
                const olasiAkts = sayiParcalar.find(n => n >= 1 && n <= 30);
                if (!isNaN(olasiAkts)) solAKTS = olasiAkts;
            }

            if (!dersAdiSol) continue;

            if (dersAdiSol.includes(dersAdiSag) || dersAdiSag.includes(dersAdiSol)) {
                let renkClass = "gray";
                const notRiski = harfNotu && !gecerliNotlar.includes(harfNotu);
                const aktsRiski = (!isNaN(solAKTS) && !isNaN(sagAKTS) && sagAKTS > solAKTS);

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
