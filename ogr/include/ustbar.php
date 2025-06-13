<?php 


session_start();

if (isset($_SESSION['tc'])) {
    $tc = $_SESSION['tc'];
    
} else {
    echo "TC bilgisi eksik! Lütfen tekrar giriş yapın.";
    header("Location: ../index.php");
    exit();
}


include "../db.php";
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Muafiyet Sistemi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
    body {
    font-family: 'Poppins', sans-serif;
    background-image: url(https://ogr.nku.edu.tr/assets/images/arkaplanbeyaz.jpg) !important;
    background-size: cover;
    background-position: center;
    margin: 0;
    padding: 0;
    font-size: 0.875rem; /* Küçük font boyutu */
}

header {
    background-color: #003366;
    color: white;
    padding: 10px 15px; /* Küçük padding */
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

header img {
    height: 50px; /* Küçük logo */
}

.menu a {
    color: white;
    text-decoration: none;
    margin: 0 10px; /* Küçük margin */
    font-weight: 400; /* Hafif font ağırlığı */
}

.menu a:hover {
    text-decoration: underline;
}

.container {
    margin: 15px auto;
    padding: 15px;
    background-color: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    max-width: 1000px; /* Küçük ekranlar için max-width */
}

h1 {
    text-align: center;
    color: #003366;
    font-size: 1.2rem; /* Daha küçük başlık fontu */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
    background-color: #f9f9f9;
}

table, th, td {
    border: 1px solid #ddd;
}

th {
    background-color: #003366;
    color: white;
}

th, td {
    padding: 8px; /* Küçük padding */
    text-align: center;
    font-size: 0.875rem; /* Küçük font */
}

.grid-container {
    display: flex;
    gap: 20px; /* Daha küçük boşluk */
    margin-top: 20px;
}

.grid-item {
    flex: 1;
    background-color: #f1f5f9;
    padding: 15px; /* Daha küçük padding */
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

select, button, input {
    margin-bottom: 12px; /* Küçük margin */
    padding: 8px; /* Küçük padding */
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 5px;
}

button {
    background-color: #003366;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #005bb5;
}

.ders-detay {
    font-size: 0.75em; /* Daha küçük yazı */
    color: #555;
    margin-top: 8px;
}

.light {
    --side: white;
    --bg: whitesmoke;
    --color: black;
    --border: lightgray;
    --shadow: #e7e7e7;
    --bg-pro: #dfebfa;
    --br-pro: #aec6df;
    --hover: whitesmoke;
}

.dark {
    --side: black;
    --bg: black;
    --color: white;
    --border: #343131;
    --shadow: #75768c;
    --bg-pro: #444cb7;
    --br-pro: #b4c3ff;
    --hover: #121212;
}

body {
    font-family: sans-serif;
    background: var(--bg);
    color: var(--color);
    margin: 5px;
}

.side {
    background: var(--side);
    height: 100vh;
    border: 1px solid var(--border);
    max-width: 280px; /* Küçük side bar */
    border-radius: 8px;
    box-shadow: 0 0 1px 0px var(--shadow);
    overflow-x: auto;
}

img {
    object-fit: cover;
}

hr {
    border-bottom: none;
    border-left: none;
    border-right: none;
    border-top: 1px solid var(--border);
}

.side > div > div {
    padding: 8px;
}

.side > div:not(:first-child) > div {
    cursor: pointer;
    border-radius: 4px;
}

.side > div:not(:first-child) > div:hover {
    background: var(--hover);
}

.ortala {
    display: flex;
    margin: 0;
}

a {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}



    </style>
</head>


<body class="light">
   <header style="margin-top: -10px;">
    <div>
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTF7YymXd5pXs4KVQpqeOc7jNxJSVAaju-YMQ&s" alt="NKÜ Logo">
        <span><strong>Namık Kemal Üniversitesi</strong> - Ders Muafiyet Sistemi</span>
    </div>
  
<!--
    <nav class="menu">
        <a href="#">Ana Sayfa</a>
        <a href="#">Hakkında</a>
        <a href="#">İletişim</a>
    </nav>

-->
</header>


<div class="ortala">



  <div class="side">
    <div style="
    display: flex;
    align-items: center;
    gap:3px;
    justify-content: space-between;
    padding: 14px 7px 0px 7px;
">
      <div>
        <img width="100%;"  src="../gorsel/ogr.jpg" alt="img">
      </div>

      <br>
   
    </div>
    <hr>
      
      <div style="
    background: var(--bg-pro);
    border: 1px solid var(--br-pro);
    padding: 1px 5px;
    border-radius: 3px;
">Öğrenci Bilgi Ekranı</div>
    <div style=" padding: 5px 7px 5px 7px;display: flex;flex-direction: column;gap: 10px; ">
      
      <div style="
    display: flex;
    align-items: center;
    gap: 6px;
">
       <a href="muaf_ogrenciler.php">    <span class="material-symbols-outlined"> signal_cellular_alt </span> Muaf Durumun Kontrol Et </a>
      </div>
     
      <hr style="width:100%;">
      <!--
      <div style="
    display: flex;
    align-items: center;
    gap: 10px;
">
        <span class="material-symbols-outlined">  </span> Muafiyet İşlemleri
      </div>
      
      <hr style="width:100%;">
  -->
    </div>
    <div style="padding:5px 7px 5px 7px">
      <div style="
    display: flex;
    align-items: center;
    gap: 6px;
    opacity:.3;
    cursor:not-allowed;
     padding: 5px 7px 5px 7px;
">
        <a href="../index.php">  <span class="material-symbols-outlined"> logout </span>
      <span>Çıkış</span></a>
      </div>
    </div>
  </div>

