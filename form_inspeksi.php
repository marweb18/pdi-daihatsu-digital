<?php
session_start();
if (!isset($_SESSION['login_status'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form PDI Checksheet - Daihatsu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; padding-bottom: 120px; font-family: sans-serif; }
        .card-header { background-color: #dc3545; color: white; font-weight: bold; font-size: 0.85rem; letter-spacing: 1px; }
        .btn-check:checked + .btn-outline-success { background-color: #198754; color: white; }
        .btn-check:checked + .btn-outline-danger { background-color: #dc3545; color: white; }
        .btn-check:checked + .btn-outline-secondary { background-color: #6c757d; color: white; }
        .summary-box { background: white; border-top: 1px solid #eee; padding: 15px 0; position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); }
        .list-group-item { font-size: 0.85rem; padding: 12px 15px; border-bottom: 1px solid #f1f1f1; }
        .form-label-sm { font-size: 0.75rem; font-weight: 700; color: #666; margin-bottom: 3px; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm fw-bold border-2">← KEMBALI</a>
        <div class="text-end">
            <small class="text-muted d-block" style="font-size: 0.7rem;">Inspektor:</small>
            <span class="text-danger fw-bold"><?= $_SESSION['nama_lengkap']; ?></span>
        </div>
    </div>

    <form id="pdiForm" action="simpan_inspeksi.php" method="POST">
        
        <div class="card mb-3 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header border-0 p-3">DATA UNIT & DEALER</div>
            <div class="card-body bg-white">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label-sm">NO. RANGKA (Input untuk Auto-Fill)</label>
                        <input type="text" name="no_rangka" id="no_rangka_input" class="form-control form-control-sm bg-light border-0" placeholder="Contoh: MHKF1..." required onchange="ambilDataAPI()">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">MODEL</label>
                        <input type="text" name="model" class="form-control form-control-sm bg-light border-0" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">WARNA</label>
                        <input type="text" name="warna" class="form-control form-control-sm bg-light border-0" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">NO. MESIN</label>
                        <input type="text" name="no_mesin" class="form-control form-control-sm bg-light border-0" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">KM PDC</label>
                        <input type="number" name="km_pdc" class="form-control form-control-sm bg-light border-0">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">NO CABANG / DLR</label>
                        <input type="text" name="no_cabang" class="form-control form-control-sm bg-light border-0" placeholder="Contoh: DLR-Jkt-01">
                    </div>
                </div>
            </div>
        </div>

        <?php
        $categories = [
            "Body Bagian Luar" => ["All body", "Alloy wheel", "Ban serep"],
            "Sistem Kelistrikan" => ["Lampu kabin", "Lampu kombinasi", "Lampu Besar", "Lampu kecil", "Wiper + elektrikal", "Klakson", "Panel instrumen", "Read unit"],
            "Ruang Mesin" => ["Air radiator", "Air washer", "Accu", "Kondisi mesin"],
            "Interior dan AC" => ["Kondisi interior", "AC & blower", "Power window", "Central lock", "Karpet dan jok", "Dashboard dan trim"],
            "Test Jalan" => ["Transmisi dan kopling", "Sistem rem", "Kemudi dan suspensi", "Performa mesin", "Suara abnormal"],
            "Item Perlengkapan" => ["Toolkit + jack", "APAR", "Buku owner manual dan buku service", "Buku garansi + sertifikat kepemilikan", "Box tray", "Smart cleaner", "Carpocket", "Wheel log", "Form periksa", "Sertifikat anti karat", "Sertifikat anti film", "Remote alarm", "Remote audio", "Talenan plat nomor"]
        ];

        foreach ($categories as $cat => $items) : ?>
            <div class="card mb-3 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-secondary border-0 p-3"><?= strtoupper($cat) ?></div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($items as $item) : 
                        $id_safe = strtolower(str_replace([' ', '+', '&', '/'], '_', $item));
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= $item ?></span>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check opsi-ceklis" name="ceklis[<?= $id_safe ?>]" id="<?= $id_safe ?>_ok" value="OK" required>
                                <label class="btn btn-outline-success btn-sm px-2" for="<?= $id_safe ?>_ok">OK</label>
                                <input type="radio" class="btn-check opsi-ceklis" name="ceklis[<?= $id_safe ?>]" id="<?= $id_safe ?>_nok" value="NOK">
                                <label class="btn btn-outline-danger btn-sm px-2" for="<?= $id_safe ?>_nok">NOK</label>
                                <input type="radio" class="btn-check opsi-ceklis" name="ceklis[<?= $id_safe ?>]" id="<?= $id_safe ?>_na" value="NA">
                                <label class="btn btn-outline-secondary btn-sm px-2" for="<?= $id_safe ?>_na">N/A</label>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <div class="card mb-3 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-dark border-0 p-3">CATATAN / NOTES</div>
            <div class="card-body">
                <textarea name="catatan" class="form-control bg-light border-0" rows="3" placeholder="Tuliskan temuan atau catatan tambahan..."></textarea>
            </div>
        </div>

        <div class="card mb-5 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-dark border-0 p-3">STATUS KEPUTUSAN</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="status_keputusan" id="stat_ok" value="DITERIMA" required>
                        <label class="btn btn-outline-success w-100 fw-bold py-3" for="stat_ok">OK DITERIMA</label>
                    </div>
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="status_keputusan" id="stat_nok" value="DITOLAK">
                        <label class="btn btn-outline-danger w-100 fw-bold py-3" for="stat_nok">NOK DITOLAK</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="summary-box">
            <div class="container">
                <div class="d-flex justify-content-around mb-2 fw-bold" style="font-size: 0.8rem;">
                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> OK: <span id="count_ok">0</span></span>
                    <span class="text-danger"><i class="bi bi-x-circle-fill"></i> NOK: <span id="count_nok">0</span></span>
                    <span class="text-secondary"><i class="bi bi-slash-circle-fill"></i> N/A: <span id="count_na">0</span></span>
                </div>
                <div class="row g-2 px-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-dark w-100 py-2 fw-bold" onclick="alert('Pratinjau laporan belum tersedia. Silakan simpan untuk melihat hasil PDF.')">PREVIEW</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm">SIMPAN INSPEKSI</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="total_ok" id="input_total_ok" value="0">
        <input type="hidden" name="total_nok" id="input_total_nok" value="0">
        <input type="hidden" name="total_na" id="input_total_na" value="0">
    </form>
</div>

<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered mx-3">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Konfirmasi Inspeksi</h5>
            </div>
            <div class="modal-body py-3" id="konfirmasiBody" style="font-size: 0.9rem;">
                </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-bold w-100 mb-2" data-bs-dismiss="modal">KEMBALI EDIT</button>
                <button type="button" id="btnFinalSimpan" class="btn btn-danger fw-bold w-100">KONFIRMASI & SIMPAN</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const radioButtons = document.querySelectorAll('.opsi-ceklis');
    const form = document.getElementById('pdiForm');
    const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

    // 1. Fungsi Hitung Ringkasan (Real-time)
    function hitungRingkasan() {
        let ok = 0, nok = 0, na = 0;
        document.querySelectorAll('.opsi-ceklis:checked').forEach(item => {
            if (item.value === 'OK') ok++;
            else if (item.value === 'NOK') nok++;
            else na++;
        });
        document.getElementById('count_ok').innerText = ok;
        document.getElementById('count_nok').innerText = nok;
        document.getElementById('count_na').innerText = na;
        document.getElementById('input_total_ok').value = ok;
        document.getElementById('input_total_nok').value = nok;
        document.getElementById('input_total_na').value = na;
    }

    radioButtons.forEach(radio => radio.addEventListener('change', hitungRingkasan));

    // 2. Fungsi Fetch API (Ambil Data Unit Otomatis)
    function ambilDataAPI() {
        let noRangka = document.getElementById('no_rangka_input').value;
        if (noRangka !== "") {
            console.log("Menghubungi API Pusat untuk: " + noRangka);
            
            // Catatan: Ini simulasi API. Link asli minta ke tim IT Pusat Daihatsu.
            fetch('https://api.daihatsu-pusat.com/v1/unit/' + noRangka)
                .then(response => response.json())
                .then(data => {
                    document.getElementsByName('model')[0].value = data.model;
                    document.getElementsByName('warna')[0].value = data.warna;
                    document.getElementsByName('no_mesin')[0].value = data.no_mesin;
                    alert("Data Unit ditemukan di database pusat!");
                })
                .catch(err => console.log("Gagal memanggil API pusat, silakan isi manual."));
        }
    }

    // 3. Logika Modal Konfirmasi
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let model = document.getElementsByName('model')[0].value;
        let rangka = document.getElementById('no_rangka_input').value;
        let totalOk = document.getElementById('count_ok').innerText;
        let totalNok = document.getElementById('count_nok').innerText;
        
        let bodyHtml = `
            <div class="bg-light p-3 rounded-3 mb-3">
                <div class="small text-muted mb-1">DATA UNIT:</div>
                <div class="fw-bold">${model}</div>
                <div class="text-secondary small">${rangka}</div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Total Item OK:</span> <span class="text-success fw-bold">${totalOk}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Total Item NOK:</span> <span class="text-danger fw-bold">${totalNok}</span>
            </div>
            <hr>
            <p class="text-center text-muted small">Pastikan semua data sudah benar sebelum menyimpan ke database.</p>
        `;
        document.getElementById('konfirmasiBody').innerHTML = bodyHtml;
        modal.show();
    });

    document.getElementById('btnFinalSimpan').addEventListener('click', () => {
        form.submit();
    });
</script>
</body>
</html>