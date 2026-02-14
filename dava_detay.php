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
$dava_id      = intval($_GET["id"]);
$kullanici_id = $_SESSION["kullanici_id"];

// DAHA ÖNCE ÇÖZMÜŞSE dava_sonuc.php SAYFASINA GİT
$kontrol = $conn->prepare("
    SELECT * FROM cozulen_davalar
    WHERE user_id = ? AND dava_id = ?
");
$kontrol->bind_param("ii", $kullanici_id, $dava_id);
$kontrol->execute();
$cozum_kayit = $kontrol->get_result();

if ($cozum_kayit->num_rows > 0) {
    echo "<script>
            alert('Bu davayı daha önce çözdün!');
            window.location.href = 'dava_sonuc.php?id=$dava_id';
          </script>";
    exit;
}


// NOT KAYDETME (INSERT / UPDATE)
if (isset($_POST["not_kaydet"])) {

    $not_metni = $_POST["not_metni"];

    // Bu kullanıcı bu dava için not eklemiş mi?
    $sorgu = $conn->prepare("
        SELECT not_id 
        FROM dedektif_notlari
        WHERE dava_id = ? AND user_id = ?
    ");
    $sorgu->bind_param("ii", $dava_id, $kullanici_id);
    $sorgu->execute();
    $kontrol = $sorgu->get_result();

    if ($kontrol->num_rows > 0) {
        $guncelle = $conn->prepare("
            UPDATE dedektif_notlari
            SET not_metni = ?
            WHERE dava_id = ? AND user_id = ?
        ");
        $guncelle->bind_param("sii", $not_metni, $dava_id, $kullanici_id);
        $guncelle->execute();

    } else {
        $ekle = $conn->prepare("
            INSERT INTO dedektif_notlari (user_id, dava_id, not_metni)
            VALUES (?, ?, ?)
        ");
        $ekle->bind_param("iis", $kullanici_id, $dava_id, $not_metni);
        $ekle->execute();
    }

    header("Location: dava_detay.php?id=" . $dava_id);
    exit;
}

// davanın genel bilgileri
$dava_sql = "SELECT * FROM davalar WHERE dava_id = $dava_id";
$dava = $conn->query($dava_sql)->fetch_assoc();

// MAKTÜL BİLGİLERİ
$maktul_sql = "SELECT * FROM maktuller WHERE dava_id = $dava_id";
$maktuller = $conn->query($maktul_sql);

// ŞÜPHELİLER
$supheli_sql = "SELECT * FROM supheliler WHERE dava_id = $dava_id";
$supheliler = $conn->query($supheli_sql);
$supheliler_liste = [];

//KATİL
$katil_sql = "SELECT * FROM katiller WHERE dava_id = $dava_id";
$katil_sonuc = $conn->query($katil_sql);

$gercek_katiller = [];
while ($k = $katil_sonuc->fetch_assoc()) {
    $gercek_katiller[] = $k['katil_adi'];
}

// İPUCU
$ipucu_sql = "SELECT * FROM ipucu WHERE dava_id = $dava_id";
$ipucu = $conn->query($ipucu_sql);

// RAPOR 
$rapor = $dava["olay_raporu"];

// MEVCUT NOTU ÇEK
$mevcut_not   = "";
$not_kontrol  = $conn->prepare("
    SELECT not_metni 
    FROM dedektif_notlari
    WHERE dava_id = ? AND user_id = ?
");
$not_kontrol->bind_param("ii", $dava_id, $kullanici_id);
$not_kontrol->execute();
$sonuc = $not_kontrol->get_result();

if ($sonuc->num_rows > 0) {
    $satir       = $sonuc->fetch_assoc();
    $mevcut_not  = $satir["not_metni"];
}
$otopsiler = [];
$labs = [];
$secim_hakki = 3;
$cozulduMu = false;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $dava["dava_adi"] ?></title>
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

    <div class="sayfa">
        <section class="maktuller">
            <h2><?php echo $dava["dava_adi"]; ?></h2>
            <div class="maktul_container">
                <div class="maktul_karti">
                    <div class="supheliler_header">
                        <h4 class="maktuller_yazisi">Maktül(ler):</h4>
                    </div>
                    <div class="maktul_icerik">
                        <?php $i = 0; while($m = $maktuller->fetch_assoc())  {?>
                            <?php $madde_listesi = explode("*", $m["maktul_bilgileri"]);?>
                            <?php $otopsiler[] = $m["otopsi_raporu"]; ?>
                            <?php $labs[] = $m["lab_sonuc"]; ?>
                            
                            <div class="maktul">
                                <div class="maktul_sol">
                                    <img src="assets/image/maktuller/<?php echo $m["maktul_foto"] ?>" alt="" class="maktul_foto">
                                    <h3 class="maktul_adi"><?php echo $m["maktul_adi"];?> (<?php echo $m["maktul_yas"] ?>)</h3>
                                    <button class="tab_button" onclick="tabAc('otopsi', <?php echo $i; ?>)">Otopsi Raporu</button>
                                    <button class="tab_button" onclick="tabAc('lab', <?php echo $i; ?>)">Laboratuvar Sonucu</button>
                                </div>
                                
                                <div class="maktul_bilgi">
                                    <ul class="madde_listesi"> <?php foreach($madde_listesi as $madde) {
                                        if(trim($madde) != "") { ?>
                                        <li><?php echo trim($madde); ?></li>
                                        <?php }
                                    } ?>
                                    </ul>
                                </div>
                            </div>
                        <?php $i++; } ?>
                    </div>
                </div>
            </div>

            <div class="not_container">
                <h3 id="de_not">Dedektif Notları</h3>

                <form method="POST" class="not_form">
                    <textarea name="not_metni" class="not_textarea" placeholder="Bu dava hakkında notlarınızı buraya yazın..." required><?php echo $mevcut_not; ?></textarea>
                    <button type="submit" name="not_kaydet" class="not_btn">Kaydet</button>
                </form>  
            </div>
        </section>

        <div class="rapor_container">
            <div class="rapor_karti">
                <div class="tab_buttons">
                    <button class="tab_button" onclick="tabAc('olay')">Olay Raporu</button>
                </div>
                <div class="rapor">
                    <div class="tab_content" id="olay"><pre><?php echo $rapor; ?></pre></div>
                    <div class="tab_content" id="otopsi" style="display:none;"><pre id="otopsi_metin"></pre></div>
                    <div class="tab_content" id="lab" style="display:none;"><pre id="lab_metin"></pre></div>
                    <div class="tab_content" id="ifade" style="display:none;"><pre id="ifade_metin"></pre></div>
                </div>

            </div>
        </div>
        
        

        <div class="supheliler_container">
            <button type="submit" name="ipucu_ver" class="ipucu_btn" onclick="ipucuGoster()">İpucu Ver</button>
            
            <div class="supheliler_karti">
                <div class="supheliler_header">
                    <h4 class="supheliler_yazisi">Şüpheliler:</h4>
                </div>
                <div class="supheliler_icerik">
                    <?php while($s = $supheliler->fetch_assoc())  {$supheliler_liste[] = $s;?> 
                        <div class="supheli">
                        <img src="assets/image/supheliler/<?php echo $s["supheli_foto"]; ?>" class="supheli_foto">
                            <div class="supheli_bilgi_alani">
                                <h5 class="supheli_adi"><?php echo $s["supheli_adi"]; ?></h5>
                                <button class="ifade_btn"
                                data-ifade="<?php echo htmlspecialchars($s['supheli_ifade'], ENT_QUOTES, 'UTF-8'); ?>"
                                onclick="ifadeGoster(this)">
                                    İfadesini Oku
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                
            </div>
            
            <div class="katil_kim_karti">    
                <?php foreach($supheliler_liste as $s) { ?>
                    <div class="secim">
                        <input type="checkbox" 
                        value="<?php echo $s['supheli_adi']; ?>"
                        data-ad="<?php echo $s['supheli_adi']; ?>"
                        onclick="AddOrRemove(this)">
                        <label><?php echo $s['supheli_adi']; ?></label><br>
                    </div>
                <?php } ?>
                <h4>Seçilen Şüpheliler:</h4>
                <div id="secim_listesi"></div>
                
                <button type="button" class="supheliyi_sucla" onclick="sucla()">Suçla</button>
            </div>
        </div>
    </div>

    <!-- veriyi JS’ye aktarıyor. -->
    <script>
        const ipucu = <?php 
        $ipucular = [];
        while($i = $ipucu->fetch_assoc()) {
            $ipucular[] = '"' . addslashes($i["ipucu"]) . '"';
        }
        echo json_encode($ipucular, JSON_UNESCAPED_UNICODE);
        ?>;
    </script>

    <script>const gercekKatil = <?php echo json_encode($gercek_katiller, JSON_UNESCAPED_UNICODE); ?>;</script>
    <script>
        const davaID = <?php echo $dava_id; ?>;
    </script>

    <script>
        const otopsiler = <?php echo json_encode($otopsiler, JSON_UNESCAPED_UNICODE); ?>;
        const lablar = <?php echo json_encode($labs, JSON_UNESCAPED_UNICODE); ?>;
    </script>

    <script> 
        secim_hakki = <?php echo $secim_hakki; ?>;
        if (secim_hakki <= 0) {
            const btn = document.querySelector(".supheliyi_sucla");
            btn.disabled = true;
            btn.classList.add("pasif");
        }
    </script>

</body>