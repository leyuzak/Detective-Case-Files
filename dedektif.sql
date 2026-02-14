-- -----------------------------------------------------
-- VERITABANI OLUSTUR
-- -----------------------------------------------------

CREATE DATABASE IF NOT EXISTS dedektif
CHARACTER SET utf8
COLLATE utf8_general_ci;

USE dedektif;


-- -----------------------------------------------------
-- USERS TABLOSU
-- -----------------------------------------------------

CREATE TABLE users (
  user_id INT(11) NOT NULL AUTO_INCREMENT,
  user_name VARCHAR(100) NOT NULL,
  user_mail VARCHAR(150) NOT NULL,
  user_psswd VARCHAR(255) NOT NULL,
  level ENUM(
    'Çaylak Gözlemci',
    'Ýz Sürücü',
    'Gölge Dedektif',
    'Karanlýk Dosyalar Ustasý',
    'Efsanevi Dedektif'
  ) DEFAULT 'Çaylak Gözlemci',
  PRIMARY KEY (user_id),
  UNIQUE KEY uq_users_mail (user_mail)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- -----------------------------------------------------
-- DAVALAR TABLOSU
-- -----------------------------------------------------

CREATE TABLE davalar (
  dava_id INT(11) NOT NULL AUTO_INCREMENT,
  dava_adi VARCHAR(255) DEFAULT NULL,
  dava_kisa_aciklama TEXT DEFAULT NULL,
  dava_zorluk VARCHAR(50) DEFAULT NULL,
  olay_raporu TEXT DEFAULT NULL,
  PRIMARY KEY (dava_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- -----------------------------------------------------
-- IPUCU TABLOSU
-- -----------------------------------------------------

CREATE TABLE ipucu (
  ipucu_id INT(11) NOT NULL AUTO_INCREMENT,
  dava_id INT(11) NOT NULL,
  ipucu TEXT NOT NULL,
  PRIMARY KEY (ipucu_id),
  KEY idx_ipucu_dava (dava_id),
  CONSTRAINT fk_ipucu_dava
    FOREIGN KEY (dava_id)
    REFERENCES davalar(dava_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- -----------------------------------------------------
-- SUPHELILER TABLOSU
-- -----------------------------------------------------

CREATE TABLE supheliler (
  supheli_id INT(11) NOT NULL AUTO_INCREMENT,
  dava_id INT(11) DEFAULT NULL,
  supheli_adi VARCHAR(255) DEFAULT NULL,
  supheli_ifade TEXT DEFAULT NULL,
  supheli_foto VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (supheli_id),
  KEY idx_supheliler_dava (dava_id),
  CONSTRAINT fk_supheliler_dava
    FOREIGN KEY (dava_id)
    REFERENCES davalar(dava_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- -----------------------------------------------------
-- MAKTULLER TABLOSU
-- -----------------------------------------------------

CREATE TABLE maktuller (
  maktul_id INT(11) NOT NULL AUTO_INCREMENT,
  dava_id INT(11) DEFAULT NULL,
  maktul_adi VARCHAR(255) DEFAULT NULL,
  maktul_yas INT(11) DEFAULT NULL,
  maktul_bilgileri TEXT DEFAULT NULL,
  otopsi_raporu TEXT NOT NULL,
  lab_sonuc TEXT NOT NULL,
  maktul_foto VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (maktul_id),
  KEY idx_maktuller_dava (dava_id),
  CONSTRAINT fk_maktuller_dava
    FOREIGN KEY (dava_id)
    REFERENCES davalar(dava_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- -----------------------------------------------------
-- KATILLER TABLOSU
-- -----------------------------------------------------

CREATE TABLE katiller (
  katil_id INT(11) NOT NULL AUTO_INCREMENT,
  dava_id INT(11) DEFAULT NULL,
  katil_adi VARCHAR(255) DEFAULT NULL,
  katil_neden TEXT DEFAULT NULL,
  katil_ifade TEXT DEFAULT NULL,
  katil_foto VARCHAR(255) DEFAULT NULL,
  katil_ceza TEXT NOT NULL,
  PRIMARY KEY (katil_id),
  KEY idx_katiller_dava (dava_id),
  CONSTRAINT fk_katiller_dava
    FOREIGN KEY (dava_id)
    REFERENCES davalar(dava_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- -----------------------------------------------------
-- COZULEN DAVALAR TABLOSU
-- -----------------------------------------------------

CREATE TABLE cozulen_davalar (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  dava_id INT(11) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_cozulen_user (user_id),
  KEY idx_cozulen_dava (dava_id),
  CONSTRAINT fk_cozulen_user
    FOREIGN KEY (user_id)
    REFERENCES users(user_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_cozulen_dava
    FOREIGN KEY (dava_id)
    REFERENCES davalar(dava_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- -----------------------------------------------------
-- DEDEKTIF NOTLARI TABLOSU
-- -----------------------------------------------------

CREATE TABLE dedektif_notlari (
  not_id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  dava_id INT(11) NOT NULL,
  not_metni TEXT DEFAULT NULL,
  PRIMARY KEY (not_id),
  UNIQUE KEY uq_not_user_dava (user_id, dava_id),
  CONSTRAINT fk_not_user
    FOREIGN KEY (user_id)
    REFERENCES users(user_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_not_dava
    FOREIGN KEY (dava_id)
    REFERENCES davalar(dava_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
