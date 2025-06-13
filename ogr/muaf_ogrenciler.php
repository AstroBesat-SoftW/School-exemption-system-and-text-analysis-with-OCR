<?php    include"include/ustbar.php"; ?>

<style type="text/css">

 
        table {
            border-collapse: collapse;
            width: 100%;
            /*max-width: 1200px;
            */box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 3px 5px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td img {
            border-radius: 4px;
            max-width: 60px;
            max-height: 60px;
        }

      

     

        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }
        }

    </style>
</head>





<div class="container">
    <?php
    include "../db.php"; // Veritabanı bağlantısını dahil et

    // Veritabanından veri çek
    $query = "SELECT * FROM muaf_ogrenci WHERE tc = $tc"; // muaf_ogrenci tablosundaki tüm veriyi çekiyoruz
    $result = $conn->query($query);

    // Eğer sorgu başarılıysa, veriyi tablo şeklinde göster
    if ($result->num_rows > 0) {
        // HTML tablosu başlangıcı
        echo "<table>
                <tr>
                    <th>İsim</th>
                    <th>Soyisim</th>
                    <th>TC</th>
                    <th>Telefon</th>
                    <th>Geldiği Okul</th>
                    <th>Geldiği Bölüm</th>
                    <th>Gittiği Okul</th>
                    <th>Gittiği Bölüm</th>
                    <th>Kayıt Tarihi</th>
                    <th>Muaf Belgesi</th>
                    <th>Transkript</th>
                    <th>Ders İçerikleri</th>
                </tr>";

        // Veritabanı sonuçlarını döngü ile tabloya ekle
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['isim']) . "</td>
                    <td>" . htmlspecialchars($row['soyisim']) . "</td>
                    <td>" . htmlspecialchars($row['tc']) . "</td>
                    <td>" . htmlspecialchars($row['telefon']) . "</td>
                    <td>" . htmlspecialchars($row['geldigi_okul']) . "</td>
                    <td>" . htmlspecialchars($row['geldigi_bolum']) . "</td>
                    <td>" . htmlspecialchars($row['gittigi_okul']) . "</td>
                    <td>" . htmlspecialchars($row['gittigi_bolum']) . "</td>
                    <td>" . htmlspecialchars($row['tarih']) . "</td>
                    <td><a href='../ogrenciler/" . htmlspecialchars($row['muaf_gorsel']) . "' > <img src='../ogrenciler/" . htmlspecialchars($row['muaf_gorsel']) . "' alt='Görsel' ></a></td>
                    <td><a href='../ogrenciler/" . htmlspecialchars($row['transkript']) . "' target='_blank'><img src='../gorsel/pdf.png' ></a></td>
                    <td><a href='../ogrenciler/" . htmlspecialchars($row['ders_icerigi']) . "' target='_blank'><img src='../gorsel/pdf.png' ></a></td>
                </tr>";
        }

        // Tablo bitişi
        echo "</table>";
    } else {
        echo "<p>Kayıt bulunamadı.</p>";
    }

    // Veritabanı bağlantısını kapat
    $conn->close();
    ?>

</div>


</div>
</body>
</html>


















   