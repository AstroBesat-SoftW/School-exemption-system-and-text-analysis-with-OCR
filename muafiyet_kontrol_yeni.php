 <?php    include"include/ustbar.php"; ?>


    <style>
     
        .container {
            max-width: 1200px;
            margin: 40px auto;
          
        }
      

  /* muafiyet en alt işşemler için */
     button {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #muafiyet-form {
            margin-top: 20px;
            display: none;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #f9f9f9;
        }

        #onay-button {
            display: none;
            background-color: darkgreen;
        }

        #gorsel-container {
            text-align: center;
            margin-top: 20px;
            display: none;
        }

        #gorsel-container img {
            max-width: 90%;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }






        #notification {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            color: white;
            text-align: center;
            display: none;
            z-index: 1000;
        }
        #notification.success { background-color: green; }
        #notification.error { background-color: red; }
    </style>
</head>




<div class="container">
    <h1>Ders Muafiyet Kontrolü <br> <i style="font-size: 16px;"> Öğrenci İşleri İçin Hazırlanmıştır.</i></h1>

    <div class="grid-container">
        <!-- Sol Taraf -->
        <div class="grid-item">
            <h3>Öğrencinin Geldiği Üniversite</h3>
           
<?php
if (isset($_POST['submit'])) {
    $upload_dir = __DIR__ . "\\uploads\\";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $pdf = $_FILES['pdf_file'];
    $pdf["name"] = "transkript.pdf";
    $target = $upload_dir . basename($pdf["name"]);

    if (move_uploaded_file($pdf["tmp_name"], $target)) {
        $python_path = 'C:\\Users\\Behsat\\AppData\\Local\\Programs\\Python\\Python310\\pythonw.exe';
        $python_script = __DIR__ . "\\pdf_isle.py";

        $komut = "\"$python_path\" \"$python_script\" " . escapeshellarg($target) . " 2>&1";
        exec($komut, $output, $return_var);
        
        echo "<pre>";
       
        echo implode("\n", $output);
        echo "</pre>";

        if ($return_var === 0) {
            $sonuc = file_get_contents(__DIR__ . "\\sonuc.txt");
            echo "<h2>İşlenen Sonuç:</h2><pre>" . htmlspecialchars($sonuc) . "</pre>";
        } else {
            echo "Python script çalıştırılırken hata oluştu.";
        }
    } else {
        echo "Dosya yüklenemedi.";
    }
}
?>



<form method="post" enctype="multipart/form-data">
    <input type="file" name="pdf_file" required>
    <button type="submit" name="submit">PDF'yi Gönder</button>
</form>



        </div>

        <!-- Sağ Taraf -->
        <div class="grid-item">
            <h3>Okulumuza Gelmek İstiyor</h3>
            <form id="sag_form">


                <select id="universite_sec_sag" onchange="getBolumler('sag')">
                    <option value="">Üniversite Seçin</option>
                    <?php
                    $universiteler->data_seek(0);
                    while ($row = $universiteler->fetch_assoc()) {
                        if ($row['universite_ad'] === "Namık Kemal Üniversitesi") {
                            echo "<option value='{$row['id']}'>{$row['universite_ad']}</option>";
                        }
                        
                    }
                    ?>
                </select>

                
                     <select id="bolum_sec_sag" onchange="getDersler('sag');" disabled>
                    <option value="">Bölüm Seçin</option>
                </select>
               <div id="sonuc_container">
  <table id="ders_listesi_sag">
                    <tr>
                        <th>Ders Kodu</th>
                        <th>Ders Adı</th>
                        <th>AKTS</th>
                        <th>T-U</th>
                        <th>Harf Notu</th>
                    </tr>
                </table>
</div>

              
            </form>
        </div>
    </div>


    

    <script>


        document.getElementById('muafiyet-buton').addEventListener('click', function () {
            html2canvas(document.getElementById('eklenen_dersler')).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                document.getElementById('tablo-gorsel').src = imgData;
                document.getElementById('gorsel-data').value = imgData;
                document.getElementById('gorsel-container').style.display = 'block';
                document.getElementById('muafiyet-form').style.display = 'block';
                document.getElementById('onay-button').style.display = 'block';
            });
        });

     document.getElementById('onay-button').addEventListener('click', function () {
    const form = document.getElementById('muafiyet-formu');
    const formData = new FormData(form);

   fetch('upload.php', {
    method: 'POST',
    body: formData
})
.then(response => {
    if (!response.ok) {
        throw new Error('HTTP hatası: ' + response.status);  // Hata kodunu yakala
    }
    return response.json();
})
.then(data => {
    if (data.error) {
        alert('Bir hata oluştu: ' + data.error);
    } else if (data.message) {
        if (data.message === 'Dosyalar ve veritabanı kaydı başarıyla tamamlandı!') {
             alert('İşlem Gerçekleşti');
        }
    } else {
        alert('Bilinmeyen bir durum oluştu.');
    }
})
.catch(error => {
    console.error('Hata:', error);
    alert('Bir hata oluştu.');
});

});



        // bu kısım ileri işleminden sonra seçilen üniversite ve bölüm için:
         // Bölümleri getirme fonksiyonu
     
    function getBolumler2(side) {
        const universiteSec2 = document.getElementById(side === 'ileri_bolum_sec' ? 'gel_okul' : '');
        const bolumSec2 = document.getElementById(side === 'ileri_bolum_sec' ? 'gel_bolum' : '');

        if (universiteSec2.value === "") {
            bolumSec2.disabled = true;
            bolumSec2.innerHTML = "<option value=''>Bölüm Seçin</option>";
            return;
        }

        fetch(`get_bolumler_2.php?universite_id=${universiteSec2.value}`)
            .then(response => response.json())
            .then(data => {
                bolumSec2.disabled = false;
                bolumSec2.innerHTML = "<option value=''>Bölüm Seçin</option>";
                data.forEach(bolum => {
                    bolumSec2.innerHTML += `<option value="${bolum.id}">${bolum.bolum_ad}</option>`;
                });
            })
            .catch(error => {
                console.error('Bölümler alınırken hata oluştu:', error);
                bolumSec2.innerHTML = "<option value=''>Bölüm Alınamadı</option>";
            });
    }
    </script>

</div>


</div>


</body>
</html>



    <script>
        let dersSayaci = 1; // Ders sırasını tutmak için

        // Bölümleri getirme fonksiyonu
        function getBolumler(side) {
            const universiteSec = document.getElementById(side === 'sol' ? 'universite_sec' : 'universite_sec_sag');
            const bolumSec = document.getElementById(side === 'sol' ? 'bolum_sec_sol' : 'bolum_sec_sag');

            if (universiteSec.value === "") {
                bolumSec.disabled = true;
                bolumSec.innerHTML = "<option value=''>Bölüm Seçin</option>";
                return;
            }

            fetch(`get_bolumler.php?universite_id=${universiteSec.value}`)
                .then(response => response.json())
                .then(data => {
                    bolumSec.disabled = false;
                    bolumSec.innerHTML = "<option value=''>Bölüm Seçin</option>";
                    data.forEach(bolum => {
                        bolumSec.innerHTML += `<option value="${bolum.id}">${bolum.bolum_ad}</option>`;
                    });
                });
        }

// Dersleri getirme fonksiyonu
function getDersler(side) {
    const bolumSec = document.getElementById(side === 'sol' ? 'bolum_sec_sol' : 'bolum_sec_sag');
    const dersListesi = document.getElementById(side === 'sol' ? 'ders_listesi_sol' : 'ders_listesi_sag');

    if (bolumSec.value === "") return;

    fetch(`get_dersler.php?bolum_id=${bolumSec.value}`)
        .then(response => response.json())
        .then(data => {
            dersListesi.innerHTML = `
                <tr>
                    <th>Ders Kodu</th>
                    <th>Ders Adı</th>
                    <th>AKTS</th>
                    <th>T-U</th>
                    <th>Harf Notu</th>
                    <th>Ders İçeriği</th>
                </tr>
            `;
            data.forEach(ders => {
                const row = dersListesi.insertRow();
                row.innerHTML = `
                    <td>${ders.ders_kodu}</td>
                    <td>${ders.ders_adi}</td>
                    <td>${ders.ders_akts}</td>
                    <td>${ders.ders_Teorik_saati}-${ders.ders_uygulama_saati}</td>
                    <td>-</td>
                    <td>${ders.ders_icerigi}</td>
                `;
            });

            
                    kaydetTumSonuc();
         
        });
}



    // otamatik kaydetmesi için 

  // Sonuçları kaydetme fonksiyonu
function kaydetTumSonuc() {
    const sonucDiv = document.getElementById('sonuc_container');
    if (!sonucDiv) {
        console.log('Sonuç container bulunamadı.');
        return;
    }

    // Metin olarak içeriği al
    const icerik = sonucDiv.innerText || sonucDiv.textContent;

    fetch('kaydet_sonuc_okul.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            tam_sonuc: icerik
        })
    })
    .then(res => res.text())
    .then(data => {
        console.log('Dosyaya kaydetme sonucu:', data);
    })
    .catch(err => {
        console.error('Kaydetme hatası:', err);
    });
}



/*      sol */
const change = document.getElementById('change');
    const body = document.body;

    change.addEventListener('click', () => {
        if (change.textContent.trim() === 'toggle_off') {  
            change.textContent = 'toggle_on';  
            body.className = 'dark';  
        } else {
            change.textContent = 'toggle_off';  
            body.className = 'light';  
        }
    });







    </script>



<style type="text/css">
    #karsilastir-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007bff;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    z-index: 999;
    width: 130px;
}

</style>
<button id="karsilastir-btn" onclick="window.location.href='karsilastir.php'">Karşılaştır</button>
