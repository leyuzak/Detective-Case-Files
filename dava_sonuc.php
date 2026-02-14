<?php 
session_start();
if(!isset($_SESSION["kullanici_id"])) {
    header("Location: giris.php");
    exit;
}
include("baglan.php");

if (!isset($_GET["id"])) {
    die("HATA: Dava ID belirtilmemiş.");
}
$dava_id = intval($_GET["id"]);

$dava_sql = "SELECT * FROM davalar WHERE dava_id = $dava_id";
$dava_sorgu = $conn->query($dava_sql);

if (!$dava_sorgu) {
    die("SQL HATASI: " . $conn->error . "<br>Sorgu: " . $dava_sql);
}

//DAVAYI ÇÖZÜLEN DAVALAR TABLOSUNA EKLE
$kullanici_id = $_SESSION["kullanici_id"];


$kontrol = $conn->prepare("
    SELECT * FROM cozulen_davalar 
    WHERE user_id = ? AND dava_id = ?
");
$kontrol->bind_param("ii", $kullanici_id, $dava_id);
$kontrol->execute();
$sonuc = $kontrol->get_result();


if ($sonuc->num_rows == 0) {

    $ekle = $conn->prepare("
        INSERT INTO cozulen_davalar (user_id, dava_id)
        VALUES (?, ?)
    ");
    $ekle->bind_param("ii", $kullanici_id, $dava_id);
    $ekle->execute();
}

// KATİL
$katil_sql = "SELECT * FROM katiller WHERE dava_id = $dava_id";
$katiller = $conn->query($katil_sql);

$katil_adi   = [];
$katil_ifade = [];
$katil_neden = [];
$katil_ceza  = [];
$katil_foto  = [];

while($k = $katiller->fetch_assoc())  { 
    $katil_adi[]   = $k['katil_adi'];
    $katil_ifade[] = $k['katil_ifade'];
    $katil_neden[] = $k['katil_neden'];
    $katil_ceza[]  = $k['katil_ceza'];
    $katil_foto[]  = $k['katil_foto'];
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEBRİKLER DEDEKTİF</title>
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

    <?php for ($j = 0; $j < count($katil_adi); $j++) { ?>
        <script>alert("<?php echo $katil_adi[$j]; ?>" + " : " + "<?php echo $katil_neden[$j]; ?>");</script>
    <?php } ?>
    
    <h2 id="mutluluk">Seninle çalışmak bir mutluluktu dedektif!</h2>
    <p class="daha_fd">Bizimle birlikte daha fazla davaya bakmak istersen <a href="davalar.php">Davalara göz at.</a> </p>

    <div class="katiller_container">
    <?php for ($i = 0; $i < count($katil_adi); $i++) { ?>
        
        <div class="katiller_karti">

            <div class="katil_resim_isim">
                <img src="assets/image/katiller/<?php echo $katil_foto[$i]; ?>" class="katil_foto">

                <h2 class="katil_adi"><?php echo $katil_adi[$i]; ?></h2>

                <a href="#" class="katil_ceza"
                   onclick="cezayiGoster(<?php echo $i; ?>); return false;">
                   Mahkeme Kararını Gör...
                </a>
            </div>

            <div class="belge">
                <div class="belge_content" id="belge_<?php echo $i; ?>">
                    <pre><?php echo $katil_ifade[$i]; ?></pre>
                </div>
            </div>

        </div>

    <?php } ?>
</div>

<script>
    let cezalar = <?php echo json_encode($katil_ceza); ?>;
</script>


</body>