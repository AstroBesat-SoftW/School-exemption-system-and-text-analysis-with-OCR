<?php


// Veritabanı bağlantısı
 include"db.php";

// Doğrulama kodları
$validCodes = [
    "kod1.png" => "OQLTK",
    "kod2.png" => "PTHB5",
    "kod3.png" => "COWVM",
    "kod4.png" => "ZVSXI",
    "kod5.png" => "KFTYB",
    "kod6.png" => "8K9OJ",
    "kod7.png" => "2RIK2",
    "kod8.png" => "ZBOPL"
];

 $hata = "";
// Form verileri alındığında
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yonetici_tc = $_POST['yonetici_tc'];
    $yonetici_sifre = $_POST['yonetici_sifre'];
    $dogrulama_kodu = $_POST['dogrulama_kodu'];
    $captcha_img = $_POST['captcha_img']; // Burada captcha'nın dosya adını alıyoruz

    // Captcha doğrulaması
    if (isset($validCodes[$captcha_img]) && $dogrulama_kodu === $validCodes[$captcha_img]) {
        // Veritabanında kontrol et
        $sorgu = "SELECT * FROM yonetici WHERE yonetici_tc = '$yonetici_tc' AND yonetici_sifre = '$yonetici_sifre'";
        $sonuc = $conn->query($sorgu);

        if ($sonuc->num_rows > 0) {
            // Kullanıcı doğru giriş yaptı, index.php'ye yönlendir
            header("Location: main.php");
            exit();
        } else {
            // Hatalı giriş
           // echo "Yanlış TC veya şifre!";
           $hata = "Yanlış TC veya Şifre!";
        }
    } else {
        //echo "Yanlış doğrulama kodu!";
           $hata = "Yanlış Doğrulama Kodu!"; 
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
           /* background-color: #f0f4f8;
            
            */
            background-image: url(https://ogr.nku.edu.tr/assets/images/arkaplanbeyaz.jpg) !important;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #003366;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        header img {
            height: 60px;
        }
        .menu a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
        }
        .menu a:hover {
            text-decoration: underline;
        }
    
   

    .form-container {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        width: 400px;
        padding: 30px;
        text-align: center;
        margin-top: 40px;
    }

    .form-container h2 {
        margin-bottom: 25px;
        color: #2c3e50;
        font-weight: bold;
    }

    .form-container input {
        width: 100%;
        padding: 12px;
        margin: 15px 0;
        border: 1px solid #dcdde1;
        border-radius: 6px;
        font-size: 15px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .form-container input:focus {
        border-color: #3498db;
        outline: none;
    }

    .captcha-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-container img {
        height: 28px;
        border-radius: 4px;
    }

    .form-container button {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        background-color: #3498db;
        border: none;
        border-radius: 6px;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container button:hover {
        background-color: #2980b9;
    }

    .form-container .secondary-btn {
        background-color: #1abc9c;
    }

    .form-container .secondary-btn:hover {
        background-color: #16a085;
    }

    .form-container .link {
        display: block;
        margin-top: 15px;
        font-size: 14px;
        color: #3498db;
        text-decoration: none;
    }

    .form-container .link:hover {
        text-decoration: underline;
    }

    .captcha-refresh-btn {
        background-color: #e67e22;
        border: none;

        border-radius: 4px;
        color: #fff;
        width: 20% !important;
        padding: 8px 12px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .captcha-refresh-btn:hover {
        background-color: #d35400;
    }

    @media (max-width: 480px) {
        .form-container {
            width: 90%;
            padding: 20px;
        }

        .captcha-container {
            flex-direction: column;
        }

        .captcha-container input {
            width: 100%;
            margin: 10px 0;
        }

        .captcha-container img {
            margin-bottom: 10px;
        }
    }



/* sağ alt bildirim */

    .notification {
            position: fixed;
            right: 20px;
            bottom: -200px;
            width: 320px;
            background-color: #f1f1f1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease-in-out;
        }
        .notification.show {
            bottom: 20px;
            opacity: 1;
            transform: translateY(0);
        }
        .notification h3 {
            margin-top: 0;
        }
        .close-btn {
            cursor: pointer;
            color: #888;
            float: right;
            font-size: 20px;
        }
        .close-btn:hover {
            color: #333;
        }



        /* a kaldırmak için izleri */

    a {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}



</style>
</head>
<body>
<header>
    <div>
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTF7YymXd5pXs4KVQpqeOc7jNxJSVAaju-YMQ&s" alt="NKÜ Logo">
        <span><strong>Namık Kemal Üniversitesi</strong> - Ders Muafiyet Sistemi</span>
    </div>
   <!-- <nav class="menu">
        <a href="#">Ana Sayfa</a>
        <a href="#">Hakkında</a>
        <a href="#">İletişim</a>
    </nav>  -->
</header>

<center>
    <div class="form-container">
        <h2>E-Öğrenci Sistemi Girişi</h2>
        <?php echo $hata;    ?>
        <form action="" method="post">
            <input type="text" id="yonetici_tc" name="yonetici_tc" placeholder="Yönetici TC" required>
            <input type="password" id="yonetici_sifre" name="yonetici_sifre" placeholder="Şifre" required>
            <div class="captcha-container">
                <input type="text" id="dogrulama_kodu" name="dogrulama_kodu" placeholder="Doğrulama Kodu" required style="width: 50%; margin-right: 10px;">
                <img id="captcha-img" src="" alt="Captcha">
                <button type="button" class="captcha-refresh-btn" onclick="getRandomCaptcha()">Yenile</button>
            </div>
            <!-- Doğrulama kodunun dosya adı, PHP'ye gönderilecektir -->
            <input type="hidden" id="captcha-img-filename" name="captcha_img" value="">
            <button type="submit">Giriş Yap</button>
           <a href="https://lms.nku.edu.tr/Account/LoginBefore"> <button type="button" class="secondary-btn">Uzem</button></a>
        </form>
        <a href="index_ogrenci_sorgu.php" class="link">Öğrenci Muaf Sorgulama Ekranı</a>
       <a href="?bilgi=no" class="link" id="getInfo">Öğrenci Numaranı Bilmiyor musun?</a>
    </div>
</center>

<script src="main.js"></script>
</body>

  <!--  <script>
        function getRandomCaptcha() {
            const captchaIndex = Math.floor(Math.random() * 8) + 1;
            document.getElementById('captcha-img').src = `kod_dogrulama/kod${captchaIndex}.png`;
        }

        document.addEventListener('DOMContentLoaded', getRandomCaptcha);
    </script> -->
    <script>
    const validCodes = {
    "kod1.png": "OQLTK",
    "kod2.png": "PTHB5",
    "kod3.png": "COWVM",
    "kod4.png": "ZVSXI",
    "kod5.png": "KFTYB",
    "kod6.png": "8K9OJ",
    "kod7.png": "2RIK2",
    "kod8.png": "ZBOPL"
};

function getRandomCaptcha() {
    const captchaIndex = Math.floor(Math.random() * 8) + 1;
    const captchaFileName = `kod${captchaIndex}.png`;
    document.getElementById('captcha-img').src = `kod_dogrulama/${captchaFileName}`;
    document.getElementById('captcha-img').setAttribute('data-code', captchaFileName);
    document.getElementById('captcha-img-filename').value = captchaFileName; // Burada captcha dosya adı inputa aktarılır
}

document.addEventListener('DOMContentLoaded', getRandomCaptcha);

</script>





    


<div class="notification" id="notificationBox">
    <span class="close-btn" onclick="closeNotification()">&times;</span>
    <h3>Öğrenci Bilgilendirme</h3>
    <p>Yeni kayıt olacağınızdan kaynaklı bunun için 
    <br><b>okul numarası şart değil</b>. Bilgi almak için açılan ekranda okul olarak  <b>geldiğiniz okul</b> Şifre Olarakte <b>TC</b> girmeniz yeterli olacak. </p>

<script>
    document.getElementById('getInfo').addEventListener('click', function(event) {
        event.preventDefault(); // Sayfa yenilenmesini engeller
        let notification = document.getElementById('notificationBox');
        notification.classList.add('show');
        
        // 10 saniye sonra mesaj kutusu kendiliğinden kapanır
        setTimeout(() => {
            closeNotification();
        }, 10000);
    });

    function closeNotification() {
        let notification = document.getElementById('notificationBox');
        notification.classList.remove('show');
    }
</script>

</body>
</html>
