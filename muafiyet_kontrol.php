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
            <form id="sol_form">
                <select id="universite_sec" onchange="getBolumler('sol')">
                    <option value="">Üniversite Seçin</option>
                    <?php
                    if ($universiteler->num_rows > 0) {
                        while ($row = $universiteler->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['universite_ad']}</option>";
                             
                        }
                    }
                    ?>
                </select>

                <select id="bolum_sec_sol" onchange="getDersler('sol')" disabled>
                    <option value="">Bölüm Seçin</option>
                </select>

                <table id="ders_listesi_sol">
                    <tr>
                        <th>Ders Kodu</th>
                        <th>Ders Adı</th>
                        <th>AKTS</th>
                        <th>T-U</th>
                        <th>Harf Notu</th>
                    </tr>
                </table>
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

                <select id="bolum_sec_sag" onchange="getDersler('sag')" disabled>
                    <option value="">Bölüm Seçin</option>
                </select>

                <table id="ders_listesi_sag">
                    <tr>
                        <th>Ders Kodu</th>
                        <th>Ders Adı</th>
                        <th>AKTS</th>
                        <th>T-U</th>
                        <th>Harf Notu</th>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <h3>Eklenen Dersler</h3>
    <button type="button" id="add_row_btn">Yeni Satır Ekle</button>
<button type="button" id="compare_btn">Dersleri Karşılaştır</button> <!-- Yeni buton ekledik -->

    <table id="eklenen_dersler">
        <tr>
            <th>#</th>
            <th>Geldiğiniz Üniversite</th>
            <th>Gitmek İstediğiniz Üniversite</th>
            <th>Harf Notu</th>
            <th>Sonuç</th>
        </tr>
    </table>
  <center>   <button type="button"  id="muafiyet-buton" style="width: 50% !important; background-color: rgba(0, 90, 0, 0.7);"> Muaf İşlemleri için İlerle   </button>
  </center>


   <div id="gorsel-container">
        <h3>Muafiyet Belgesi</h3>
        <img id="tablo-gorsel" alt="Tablo Görseli">
    </div>

    <div id="muafiyet-form">
        <h3>Muafiyet Bilgileri</h3>
        <form id="muafiyet-formu" enctype="multipart/form-data">
               



           
              
           <label>Geldiği Üniversite <select id="gel_okul" onchange="getBolumler2('ileri_bolum_sec')" name="gel_okul" required>>
                  
                    <?php
                    $universiteler->data_seek(0);
                    while ($row = $universiteler->fetch_assoc()) {
                        
                            echo "<option value='{$row['id']}'>{$row['universite_ad']}</option>";
                        
                        
                    }
                    ?>
                </select></label><br>
           <label>Geldiği Bölüm  <select id="gel_bolum" name="gel_bolum" disabled>
                    <option value="">Bölüm Seçin</option>
                </select>
</label><br>
           <label>Gideceği Üniversite  
                     
                    <?php
                    $universiteler->data_seek(0);
                    while ($row = $universiteler->fetch_assoc()) {
                        if ($row['universite_ad'] === "Namık Kemal Üniversitesi") {
                            echo "
                             <input type='text' name='git_okul' value='{$row['universite_ad']}' readonly>";
                        }
                        
                    }     
                    ?>
                </label><br>
           <label>Gideceği Bölüm  <select id="git_bolum" name="git_bolum" required>>
                  
                    <?php
                    

 

 

                   // bölümleri getir   ------------> 7 olma sebebi veri tabanında namık kemal 7
                    $bolumler_sorgu = "SELECT id, bolum_ad FROM bolumler where universite_id = 7";
                    $bolumler = $conn->query($bolumler_sorgu);


                    $bolumler->data_seek(0);
                    while ($row = $bolumler->fetch_assoc()) {
                    
                            echo "<option value='{$row['bolum_ad']}'>{$row['bolum_ad']}</option>";
                        
                         
                    }   
                    ?>
                </select></label><br>




            <label>İsim: <input type="text" name="isim" required></label><br>
            <label>Soyisim: <input type="text" name="soyisim" required></label><br>
            <label>TC: <input type="text" name="tc" required></label><br>
            <label>Telefon: <input type="text" name="telefon" required></label><br>
            <label>Muafiyet Yılı: <input type="date" name="kayit_tarihi" required></label><br>
            <label>Transkript (PDF): <input type="file" name="transkript_pdf" accept=".pdf" required></label><br>
            <label>Ders İçerikleri (PDF): <input type="file" name="icerikler_pdf" accept=".pdf" required></label><br>
            <input type="hidden" id="gorsel-data" name="gorsel_data">
        </form>
    </div>
<div id="notification"></div>
    <center>
        <button id="onay-button">Onaylamak İster Misiniz?</button>
    </center>
    
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
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
                });
        }

     



    // Dersleri dinamik olarak doldur
    function fetchDersler(side, selectElement) {
        const bolumSec = document.getElementById(side === 'sol' ? 'bolum_sec_sol' : 'bolum_sec_sag');
        if (bolumSec.value === "") return;

        fetch(`get_dersler.php?bolum_id=${bolumSec.value}`)
            .then(response => response.json())
            .then(data => {
                selectElement.innerHTML = "<option value=''>Ders Seçin</option>";
                data.forEach(ders => {
                    const option = document.createElement('option');
                    option.value = ders.ders_kodu;
                    option.textContent = `${ders.ders_kodu} - ${ders.ders_adi}`;
                    // Detay bilgilerini option'a data-attribute olarak ekle
                    option.setAttribute('data-akts', ders.ders_akts);
                    option.setAttribute('data-teorik', ders.ders_Teorik_saati);
                    option.setAttribute('data-uygulama', ders.ders_uygulama_saati);
                     option.setAttribute('data-icerigi', ders.ders_icerigi);
                    
                    selectElement.appendChild(option);
                });
            });
    }

    document.getElementById('add_row_btn').addEventListener('click', function() {
        const eklenenDersler = document.getElementById("eklenen_dersler");
        
        // Her butona tıklanışta yalnızca bir satır ekle
        const row = eklenenDersler.insertRow();
        row.innerHTML = `
            <td>${dersSayaci++}</td>
            <td>
                <select class="sol_ders_select" onchange="dersSecildi(this, 'sol')">
                    <option value="">Ders Seçin</option>
                </select>
                <div class="sol_ders_detay ders-detay"></div>
            </td>
            <td>
                <select class="sag_ders_select" onchange="dersSecildi(this, 'sag')">
                    <option value="">Ders Seçin</option>
                </select>
                <div class="sag_ders_detay ders-detay"></div>
            </td>
            <td>
                <input type="text" class="harf_notu_input" placeholder="Harf Notu" oninput="harfNotuDegisti(this)">
            </td>
            <td id="sonuc_sebep"></td>
        `;

        // Dersler için seçenekleri doldur
        const solDersSelect = row.querySelector('.sol_ders_select');
        const sagDersSelect = row.querySelector('.sag_ders_select');
        fetchDersler('sol', solDersSelect);
        fetchDersler('sag', sagDersSelect);
    });

    // Ders seçildiğinde detayları gösterme
    function dersSecildi(selectElement, side) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const detayDiv = selectElement.nextElementSibling;

        if (!selectedOption || !selectedOption.value) {
            detayDiv.innerHTML = "";
            return;
        }

        const akts = selectedOption.getAttribute('data-akts');
        const teorik = selectedOption.getAttribute('data-teorik');
        const uygulama = selectedOption.getAttribute('data-uygulama');
        const icerik = selectedOption.getAttribute('data-icerigi');
        const row = selectElement.closest('tr');
        const solDersDetay = row.querySelector('.sol_ders_detay');
        const sagDersDetay = row.querySelector('.sag_ders_detay');

        if (side === 'sol') {
            solDersDetay.setAttribute('data-akts', akts);
            solDersDetay.setAttribute('data-teorik', teorik);
            solDersDetay.setAttribute('data-uygulama', uygulama);
             solDersDetay.setAttribute('data-icerik', icerik);

            solDersDetay.innerHTML = `(Ders Bilgisi: ${akts} AKTS, T-U: ${teorik}-${uygulama}, Ders İçeriği: ${icerik}, Harf Notu: Giriş Yap)`;
        } else {
            sagDersDetay.setAttribute('data-akts', akts);
            sagDersDetay.setAttribute('data-teorik', teorik);
            sagDersDetay.setAttribute('data-uygulama', uygulama);
            sagDersDetay.setAttribute('data-icerik', icerik);
            sagDersDetay.innerHTML = `(Ders Bilgisi: ${akts} AKTS, T-U: ${teorik}-${uygulama}, Ders İçeriği: ${icerik}, Harf Notu: -)`;
        }
    }

    // Harf notu değiştiğinde yapılacak işlem
    function harfNotuDegisti(inputElement) {
        const harfNotu = inputElement.value;
        const row = inputElement.closest('tr');
        const solDersDetay = row.querySelector('.sol_ders_detay');
        if (solDersDetay) {
            solDersDetay.innerHTML = `(Ders Bilgisi: ${solDersDetay.getAttribute('data-akts')} AKTS, T-U: ${solDersDetay.getAttribute('data-teorik')}-${solDersDetay.getAttribute('data-uygulama')}, Ders İçerigi: ${solDersDetay.getAttribute('data-icerik')}, Harf Notu: ${harfNotu})`;
        }
    }

  document.getElementById('compare_btn').addEventListener('click', function () {
    const rows = document.querySelectorAll('#eklenen_dersler tr');

    rows.forEach((row, index) => {
        if (index === 0) return; // İlk satır başlık olduğu için geç

        const solDersSelect = row.querySelector('.sol_ders_select');
        const sagDersSelect = row.querySelector('.sag_ders_select');
        const harfNotuInput = row.querySelector('.harf_notu_input');
        const sonucSebepCell = row.querySelector('#sonuc_sebep');

        if (!solDersSelect || !sagDersSelect || !sonucSebepCell) return;

        // Ders içeriklerini ve AKTS değerlerini alıyoruz
        const solIcerik = solDersSelect.options[solDersSelect.selectedIndex]?.getAttribute('data-icerigi') || '';
        const sagIcerik = sagDersSelect.options[sagDersSelect.selectedIndex]?.getAttribute('data-icerigi') || '';

        const solAkts = parseInt(solDersSelect.options[solDersSelect.selectedIndex]?.getAttribute('data-akts') || 0);
        const sagAkts = parseInt(sagDersSelect.options[sagDersSelect.selectedIndex]?.getAttribute('data-akts') || 0);

        // Harf notu verisini sayısal değere çeviriyoruz
        const harfNotu = harfNotuToSayisal(harfNotuInput?.value || 'FF');

        let sebep = "";

        // Ders içeriklerini, AKTS ve harf notunu kontrol ediyoruz
        if (solIcerik.trim() === sagIcerik.trim()) {
            if (solAkts >= sagAkts) {
                if (harfNotu >= 60) {
                    row.style.backgroundColor = 'lightgreen'; // Geçer
                    sebep = "Muaf (İçerikler aynı, AKTS yeterli, Harf Notu uygun)";
                } else {
                    row.style.backgroundColor = 'lightcoral'; // Kalır
                    sebep = "Muaf Değil (Harf Notu Yetersiz)";
                }
            } else {
                row.style.backgroundColor = 'lightcoral'; // Kalır
                sebep = "Muaf Değil (AKTS Yetersiz)";
            }
        } else {
            row.style.backgroundColor = 'lightcoral'; // Kalır
            sebep = "Muaf Değil (İçerikler Farklı)";
        }

        // Sonucu sebep kısmına yazıyoruz
        sonucSebepCell.innerHTML = sebep;
    });
});

// Harf notlarını sayısal değerlere çeviren yardımcı fonksiyon
function harfNotuToSayisal(harfNotu) {
    const harfNotlari = {
        'AA': 90, 'BA': 80, 'BB': 74, 'CB': 66, 'CC': 60,
        'DC': 50, 'DD': 40, 'FD': 30, 'FF': 20
    };
    return harfNotlari[harfNotu.toUpperCase()] || 0;
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



