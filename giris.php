<?php
session_start();

include("baglan.php");

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_mail = $_POST["user_mail"];
    $sifre = $_POST["sifre"];

    $sorgu = $conn->prepare("SELECT * FROM users WHERE user_mail = ?");
    $sorgu->bind_param("s", $user_mail);
    $sorgu->execute();
    $sonuc = $sorgu->get_result();

    if ($sonuc->num_rows == 0) {
        $hata = "Bu e-mail ile kayıtlı bir hesap bulunamadı!";
    } else {

        $kullanici = $sonuc->fetch_assoc();

        if (password_verify($sifre, $kullanici["user_psswd"])) {

            $_SESSION["kullanici_id"] = $kullanici["user_id"];
            $_SESSION["kullanici_adi"] = $kullanici["user_name"];
            $_SESSION["kullanici_level"] = $kullanici["level"];

            header("Location: davalar.php");
            exit;

        } else {
            $hata = "Şifre hatalı!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="dedektif.css">
    <script src="dedektif.js"></script>
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

    <div class="bas_giris">
        <img src="assets/gif/giris_kayit_dog.gif" id="kontrolcu_kopk" alt="">
        <h4>
            Davalarımızın bilgilerine erişmek istiyorsan senin bizim merkezimizden olduğuna emin olmamız gerek!
            Kartını doldur lütfen.
        </h4>
    </div>

    <a href="kayit.php" id="yonlendirme">Merkezimizden değilsen kayıt işlemlerini başlat. </a>
    
    <div class="giris_karti">
        <img src="assets/image/giris_dedektifi.png" alt="" id="resim">
        <div class="kayit_alani">
            <form method="POST" class="kayit_alani">
                <input type="text" name="user_mail" class="input" placeholder="E-mail">
                <input type="password" name="sifre" class="input" placeholder="Şifre">
                <input type="submit" id="btnGiris" value="Giriş">
            </form>
            <?php if($hata != "") { echo "<p style='color:red;'>$hata</p>"; } ?>
        </div>
    </div>
    
</body>