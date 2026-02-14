<?php
session_start();

include("baglan.php");

$kullanici_id = $_SESSION["kullanici_id"];

// ÇÖZÜLEN DAVA SAYISINI ÇEK
$sorgu = $conn->prepare("
    SELECT COUNT(*) AS toplam
    FROM cozulen_davalar
    WHERE user_id = ?
");
$sorgu->bind_param("i", $kullanici_id);
$sorgu->execute();
$sonuc = $sorgu->get_result()->fetch_assoc();

$cozulen_sayi = $sonuc["toplam"];
// LEVEL HESAPLA

if ($cozulen_sayi <= 2) {
    $level = "Çaylak Gözlemci";
} 
else if ($cozulen_sayi <= 5) {
    $level = "İz Sürücü";
} 
else if ($cozulen_sayi <= 9) {
    $level = "Gölge Dedektif";
} 
else if ($cozulen_sayi <= 15) {
    $level = "Karanlık Dosyalar Ustası";
} 
else {
    $level = "Efsanevi Dedektif";
}

// LEVEL’I SESSION’A YAZ
$_SESSION["kullanici_level"] = $level;

$sql = "
SELECT cd.*, d.dava_adi, d.dava_kisa_aciklama, d.dava_zorluk
FROM cozulen_davalar cd
JOIN davalar d ON cd.dava_id = d.dava_id
WHERE cd.user_id = $kullanici_id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dedektif <?php echo $_SESSION["kullanici_adi"]; ?></title>
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

    <div class="profil_alani">
        <h3> <?php echo $_SESSION["kullanici_adi"]; ?> </h3>

        <span id="omg">
            <?php 
            echo isset($_SESSION["kullanici_level"]) 
            ? $_SESSION["kullanici_level"] 
            : "Seviye bilgisi bulunamadı";
            ?>
        </span>

        <p class="istatistik">Toplam Çözülen Dava: <?php echo $cozulen_sayi; ?></p>
    </div>

    <h2 id="coz_dava">Çözülen Davalar:</h3>

    <section>
        <div class="dava_container">
            <?php while($row = $result->fetch_assoc()) { ?>
                <div class="dava_karti">
                    <div class="dava_header">
                        <a href="dava_detay.php?id=<?php echo $row['dava_id']; ?>">
                            <?php echo $row["dava_adi"]; ?>
                        </a>
                        <span class="dava_tag <?php echo strtolower($row['dava_zorluk']); ?>">
                            <?php echo $row['dava_zorluk']; ?>
                        </span>
                    </div>
                    
                    <p class="dava_aciklama">
                        <?php echo $row['dava_kisa_aciklama']; ?>
                    </p>
                </div>
            <?php } ?>
        </div>
    </section>

    <a href="hesabi_sil.php" id="hesabi_sil">Hesabı Sil :(</a>
</body>