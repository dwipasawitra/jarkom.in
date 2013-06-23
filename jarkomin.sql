SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `jarkomin` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `jarkomin` ;

-- -----------------------------------------------------
-- Table `jarkomin`.`pengguna`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`pengguna` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`pengguna` (
  `nama_login` VARCHAR(50) NOT NULL ,
  `nama_lengkap` VARCHAR(255) NOT NULL ,
  `no_handphone` VARCHAR(50) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `kredit` INT NOT NULL DEFAULT 0 ,
  UNIQUE INDEX `namaLoginPengguna_UNIQUE` (`nama_login` ASC) ,
  PRIMARY KEY (`nama_login`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`grup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`grup` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`grup` (
  `id_grup` INT NOT NULL AUTO_INCREMENT ,
  `nama_grup` VARCHAR(255) NOT NULL ,
  `pengguna` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id_grup`) ,
  INDEX `fk_GrupKontak_Pengguna_idx` (`pengguna` ASC) ,
  CONSTRAINT `fk_GrupKontak_Pengguna`
    FOREIGN KEY (`pengguna` )
    REFERENCES `jarkomin`.`pengguna` (`nama_login` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`kontak`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`kontak` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`kontak` (
  `id_kontak` INT NOT NULL AUTO_INCREMENT ,
  `nama_kontak` VARCHAR(45) NULL ,
  `no_handphone` VARCHAR(45) NOT NULL ,
  `pengguna` VARCHAR(50) NOT NULL ,
  `twitter` VARCHAR(100) NULL ,
  PRIMARY KEY (`id_kontak`) ,
  INDEX `fk_kontak_pengguna1` (`pengguna` ASC) ,
  CONSTRAINT `fk_kontak_pengguna1`
    FOREIGN KEY (`pengguna` )
    REFERENCES `jarkomin`.`pengguna` (`nama_login` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`sms_pesanan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`sms_pesanan` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`sms_pesanan` (
  `id_sms_pesanan` INT NOT NULL AUTO_INCREMENT ,
  `konten` VARCHAR(255) NULL ,
  `waktu_kirim` DATETIME NULL ,
  `kontak` INT NOT NULL ,
  `terkirim` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id_sms_pesanan`) ,
  INDEX `fk_SMSPribadiDipesan_Kontak1_idx` (`kontak` ASC) ,
  CONSTRAINT `fk_SMSPribadiDipesan_Kontak1`
    FOREIGN KEY (`kontak` )
    REFERENCES `jarkomin`.`kontak` (`id_kontak` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`grup_kontak`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`grup_kontak` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`grup_kontak` (
  `id_grup_kontak` INT NOT NULL ,
  `grup` INT NOT NULL ,
  `kontak` INT NOT NULL ,
  PRIMARY KEY (`id_grup_kontak`) ,
  INDEX `fk_grup_kontak_grup1` (`grup` ASC) ,
  INDEX `fk_grup_kontak_kontak1` (`kontak` ASC) ,
  CONSTRAINT `fk_grup_kontak_grup1`
    FOREIGN KEY (`grup` )
    REFERENCES `jarkomin`.`grup` (`id_grup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grup_kontak_kontak1`
    FOREIGN KEY (`kontak` )
    REFERENCES `jarkomin`.`kontak` (`id_kontak` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`kegiatan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`kegiatan` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`kegiatan` (
  `id_kegiatan` VARCHAR(255) NOT NULL ,
  `nama_kegiatan` TEXT NULL ,
  `pengguna` VARCHAR(50) NOT NULL ,
  `grup` INT NOT NULL ,
  `waktu_mulai_kegiatan` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_kegiatan`) ,
  INDEX `fk_kegiatan_pengguna1` (`pengguna` ASC) ,
  INDEX `fk_kegiatan_grup1` (`grup` ASC) ,
  CONSTRAINT `fk_kegiatan_pengguna1`
    FOREIGN KEY (`pengguna` )
    REFERENCES `jarkomin`.`pengguna` (`nama_login` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_kegiatan_grup1`
    FOREIGN KEY (`grup` )
    REFERENCES `jarkomin`.`grup` (`id_grup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`konfirmasi_kegiatan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`konfirmasi_kegiatan` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`konfirmasi_kegiatan` (
  `id_konfirmasi_kegiatan` INT NOT NULL ,
  `kegiatan` VARCHAR(255) NOT NULL ,
  `kontak` INT NOT NULL ,
  `konfirmasi` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`id_konfirmasi_kegiatan`) ,
  INDEX `fk_konfirmasi_kegiatan_kegiatan1` (`kegiatan` ASC) ,
  INDEX `fk_konfirmasi_kegiatan_kontak1` (`kontak` ASC) ,
  CONSTRAINT `fk_konfirmasi_kegiatan_kegiatan1`
    FOREIGN KEY (`kegiatan` )
    REFERENCES `jarkomin`.`kegiatan` (`id_kegiatan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_konfirmasi_kegiatan_kontak1`
    FOREIGN KEY (`kontak` )
    REFERENCES `jarkomin`.`kontak` (`id_kontak` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`pengingat_kegiatan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`pengingat_kegiatan` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`pengingat_kegiatan` (
  `id_pengingat_kegiatan` INT NOT NULL ,
  `kegiatan` VARCHAR(255) NOT NULL ,
  `menit_sebelumnya` INT NULL ,
  `pesan` VARCHAR(160) NULL ,
  PRIMARY KEY (`id_pengingat_kegiatan`) ,
  INDEX `fk_pengingat_kegiatan_kegiatan1` (`kegiatan` ASC) ,
  CONSTRAINT `fk_pengingat_kegiatan_kegiatan1`
    FOREIGN KEY (`kegiatan` )
    REFERENCES `jarkomin`.`kegiatan` (`id_kegiatan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jarkomin`.`ci_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `jarkomin`.`ci_sessions` ;

CREATE  TABLE IF NOT EXISTS `jarkomin`.`ci_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0' ,
  `ip_address` VARCHAR(45) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(120) NOT NULL ,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT 0 ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`session_id`) ,
  INDEX `last_activity_idx` (`last_activity` ASC) );



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `jarkomin`.`pengguna`
-- -----------------------------------------------------
START TRANSACTION;
USE `jarkomin`;
INSERT INTO `jarkomin`.`pengguna` (`nama_login`, `nama_lengkap`, `no_handphone`, `password`, `kredit`) VALUES ('wira', 'Putu Wiramaswara Widya', '083119668934', '6215f4770ee800ad5402bc02be783c26', 0);

COMMIT;
