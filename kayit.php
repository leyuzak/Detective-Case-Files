<?php
session_start();

include("baglan.php");  


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email  = trim($_POST["user_mail"]);
    $kod_ad = trim($_POST["user_name"]);
    $sifre  = trim($_POST["sifre"]);
    
    if (empty($email) || empty($kod_ad) || empty($sifre)) {
        echo "<script>alert('Lütfen tüm alanları doldurun!'); window.history.back();</script>";
        exit;
    }

    $kontrol = $conn->prepare("SELECT * FROM users WHERE user_mail = ?");
    $kontrol->bind_param("s", $email);
    $kontrol->execute();
    $sonuc = $kontrol->get_result();

    if ($sonuc->num_rows > 0) {
        echo "<script>alert('Bu email zaten kayıtlı! Giriş sayfasına yönlendiriliyorsunuz.'); 
        window.location.href = 'giris.php';</script>";
        exit;
    }

    $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);

    $level = 'Çaylak Dedektif';

    $sql = "INSERT INTO users (user_mail, user_name, user_psswd, level) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $kod_ad, $sifre_hash, $level);

    if ($stmt->execute()) {
        echo "<script>alert('Kayıt başarılı! Giriş sayfasına yönlendiriliyorsunuz.'); 
        window.location.href = 'giris.php';</script>";
        exit;
    } else {
        echo "<script>alert('Kayıt sırasında bir hata oluştu.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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
                        <span style="color:#d4a043;">Hoş geldin, <?php echo $_SESSION["kullanici_adi"]; ?></span>
                        <a id="cikis" href="cikis.php">Çıkış Yap</a>
                    <?php } else { ?>
                        <a id="giris" href="giris.php">Giriş Yap</a>
                        <a id="kayit" href="kayit.php">Kayıt Ol</a>
                    <?php } ?>
                </div>
            </nav>
    </section>

    <div class="bas_giris">
        <img src="assets/gif/ortama_giren_pikachu.gif" id="kayit_pikachu" alt="">
        <h4>
            Yeni bir dedektif aramıza katılıyorrrrr!
        </h4>
    </div>
    
    <div class="giris_karti">
        <img src="assets/image/giris_dedektifi.png" alt="" id="resim">
        <div class="kayit_alani">
            <form action="" method="POST" class="kayit_formu">
                <input type="text" name="user_mail" class="input" placeholder="E-mail">
                <input type="text" name="user_name" class="input" placeholder="Kod Adın">
                <input type="password" name="sifre" class="input" placeholder="Şifre">
                <input type="submit" id="btnGiris" value="Kayıt">
            </form>
        </div>
    </div>
    
</body>