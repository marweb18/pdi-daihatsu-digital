CREATE DATABASE db_daihatsu_pdi;
USE db_daihatsu_pdi;

CREATE TABLE inspeksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(50),
    no_rangka VARCHAR(50),
    no_mesin VARCHAR(50),
    warna VARCHAR(30),
    km_pdc INT,
    hasil_ceklis JSON, -- Menyimpan data OK/NOK dalam format JSON
    total_ok INT,
    total_nok INT,
    total_na INT,
    status_keputusan VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);