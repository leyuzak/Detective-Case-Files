<?php
session_start();
include("baglan.php");

if(!isset($_SESSION["kullanici_id"])) {
    header("Location: giris.php");
    exit;
}

$kullanici_id = $_SESSION["kullanici_id"];

// Kullanıcıyı veritabanından sil
$sil = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$sil->bind_param("i", $kullanici_id);
$sil->execute();

$conn->query("DELETE FROM dedektif_notlari WHERE user_id = $kullanici_id");
$conn->query("DELETE FROM cozulen_davalar WHERE user_id = $kullanici_id");

session_destroy();

header("Location: kayit.php?hesap_silindi=1");
exit;
?>
