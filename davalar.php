<?php 
session_start();

include("baglan.php");

$sql = "SELECT * FROM davalar";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Davalar</title>
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

    <h1>Davalar</h1> 
    <p class="tanitimyazi">Çözülmeyi bekleyen bir gizem seçin ve maceraya atılın.</p>

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

</body>