<?php 
session_start();
?>
<!DOCTYPE html>

<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, inital-scale=1.0">
        <title>Dedektiflik Oyun Platformu</title>
        <script src="dedektif.js"></script>
        <link rel="stylesheet" type="text/css" href="dedektif.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" 
        rel="stylesheet">

    </head>

    <body>
        <section id="menu">
            <a id="logo" href="dedektif.php">Dedektif</a>
            <nav>
                <a id="menu_link" href="davalar.php">DAVALAR</a>
                <div class="giris_yap_kismi">
                    <?php if(isset($_SESSION["kullanici_id"])) { ?>
                        <a id="kullanici_adi" href="profile.php">Hoş geldin, <?php echo $_SESSION["kullanici_adi"]; ?></a>
                        <a id="cikis" href="cikis.php">Çıkış Yap</a>
                    <?php } else { ?>
                        <a id="giris" href="giris.php">Giriş Yap</a>
                        <a id="kayit" href="kayit.php">Kayıt Ol</a>
                    <?php } ?>
                </div>
            </nav>
        </section>

        Leyuza Kübra

        <section id="anasayfa">
            <h1>Hadi Dava Çözelim</h1>
            <p class="tanitimyazi">İpuçları göz önünde, ama kimse bakmıyor. Kaydol, dosyaları aç ve kendi Sherlock’un ol.</p>
            <img id="dedektifkopke" src="assets/gif/dedektifkopke.gif" alt="">
            <input type="submit" value="Davaları Keşfet" id="kesfet_btn" onclick="location.href='davalar.php'">
        </section>

        williamest
    </body>
</html>