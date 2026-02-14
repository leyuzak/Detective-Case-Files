let index = 0;

function ipucuGoster() {
    if (!ipucu || ipucu.length === 0) {
        alert("Bu davada ipucu yok.");
        return;
    }

    if (index < ipucu.length) {
        alert(ipucu[index]);
        index++;
    } else {
        alert("Tüm ipuçlarını gösterdin!");
    }
}

function tabAc(tur, index) {
    document.getElementById('olay').style.display = "none";
    document.getElementById('otopsi').style.display = "none";
    document.getElementById('lab').style.display = "none";

    if (tur === "otopsi") {
        document.getElementById("otopsi").style.display = "block";
        document.getElementById("otopsi_metin").textContent = otopsiler[index];
    }

    if (tur === "lab") {
        document.getElementById("lab").style.display = "block";
        document.getElementById("lab_metin").textContent = lablar[index];
    }

    if (tur === "olay") {
        document.getElementById("olay").style.display = "block";
    }
}


function ifadeGoster(btn) {

    document.querySelectorAll('.tab_content').forEach(t => {
        t.style.display = "none";
    });

    let metin = btn.getAttribute("data-ifade");

    document.getElementById("ifade").style.display = "block";

    document.getElementById("ifade_metin").textContent = metin;
}

window.addEventListener("load", function () {
    tabAc('olay');
});


let secilenSupheliler = [];
function AddOrRemove(checkbox) {
    let ad = checkbox.value; 

    if (checkbox.checked) {
        secilenSupheliler.push(ad);
    } else {
        secilenSupheliler = secilenSupheliler.filter(item => item !== ad);
    }

    GuncelleListe();
}

function GuncelleListe() {
    let alan = document.getElementById("secim_listesi");

    if (secilenSupheliler.length === 0) {
        alan.innerHTML = "<i>Henüz seçim yok.</i>";
        return;
    }

    alan.innerHTML = secilenSupheliler
        .map(item => " " + item);
}

function sucla() {

    if (secim_hakki <= 0) {
        alert("Seçim hakkınız bitmiştir.");
        const btn = document.querySelector(".supheliyi_sucla");
        btn.disabled = true;
        btn.classList.add("pasif");
        window.location.href = "davalar.php";
        return;
    }

    if (secilenSupheliler.length === 0) {
        alert("Lütfen en az bir şüpheli seçiniz!");
        return;
    }

    let dogruMu =
        secilenSupheliler.length === gercekKatil.length &&
        secilenSupheliler.every(kisi => gercekKatil.includes(kisi));

    if (dogruMu) {
        alert("Dava çözüldü! TEBRİKLER!");
        window.location.href = "dava_sonuc.php?dogru=1&id=" + davaID;
        return;
    }

    secim_hakki--;

    if (secim_hakki < 0) secim_hakki = 0;

    alert("Yanlış seçim! Kalan hakkınız: " + secim_hakki);
    
    if (secim_hakki <= 0) {
        alert("Hiç hakkınız kalmadı. Dava çözülemedi. İyi bir dedektif olmak için yeterli değilsin. Kendini geliştirmelisin.");

        const btn = document.querySelector(".supheliyi_sucla");
        btn.disabled = true;
        btn.classList.add("pasif");

        window.location.href = "davalar.php";
        return;
    }
}



function cezayiGoster(index) {
    let cezaMetni = cezalar[index];

    document.getElementById("belge_" + index).innerHTML =
        "<pre>" + cezaMetni + "</pre>";
}