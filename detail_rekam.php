<?php 
include 'header.php';

$id = $_GET['id'];
$sql = "SELECT rekam_medis.*, 
               pasien.nama_pasien, pasien.no_rm, pasien.jenis_kelamin, pasien.tanggal_lahir, pasien.alamat,
               dokter.nama_dokter, dokter.spesialisasi,
               obat.nama_obat, obat.satuan
        FROM rekam_medis 
        JOIN pasien ON rekam_medis.id_pasien = pasien.id_pasien 
        JOIN dokter ON rekam_medis.id_dokter = dokter.id_dokter 
        JOIN obat ON rekam_medis.id_obat = obat.id_obat 
        WHERE rekam_medis.id_rekam = '$id'";

$query = mysqli_query($koneksi, $sql);
$d = mysqli_fetch_assoc($query);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <a href="rekam_medis.php" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    <button onclick="window.print()" class="btn-primary"><i class="fa-solid fa-print"></i> Cetak / Download</button>
</div>

<div class="card" style="padding: 40px;">
    <h2 style="font-size: 22px; font-weight: 800; margin-bottom: 25px;"><i class="fa-solid fa-file-invoice" style="color: #1D4ED8;"></i> Detail Rekam Medis</h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px;">
        <!-- Profil Pasien -->
        <div style="display: flex; gap: 20px; align-items: center; background: #F8FAFC; padding: 20px; border-radius: 16px;">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: #3B82F6; color: white; display: flex; align-items: center; justify-content: center; font-size: 32px;"><i class="fa-solid fa-user"></i></div>
            <div>
                <h3 style="font-size: 20px; font-weight: 800; color: #0F172A;"><?= $d['nama_pasien'] ?></h3>
                <p style="font-size: 14px; color: #475569;">No. RM : <strong><?= $d['no_rm'] ?></strong></p>
                <p style="font-size: 13px; color: #64748B;">Jenis Kelamin : <?= $d['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                <p style="font-size: 13px; color: #64748B;">Tanggal Lahir : <?= date('d-m-Y', strtotime($d['tanggal_lahir'])) ?></p>
                <p style="font-size: 13px; color: #64748B;">Alamat : <?= $d['alamat'] ?></p>
            </div>
        </div>

        <!-- Info Dokter & Periksa -->
        <div style="background: #F8FAFC; padding: 20px; border-radius: 16px;">
            <h4 style="font-size: 15px; font-weight: 700; color: #1E293B; margin-bottom: 12px;">Informasi Pemeriksaan</h4>
            <p style="font-size: 14px; margin-bottom: 8px;"><strong>Tanggal Periksa :</strong> <?= date('d/m/Y', strtotime($d['tanggal_periksa'])) ?></p>
            <p style="font-size: 14px; margin-bottom: 8px;"><strong>Dokter :</strong> <?= $d['nama_dokter'] ?> (<?= $d['spesialisasi'] ?>)</p>
            <p style="font-size: 14px;"><strong>Obat :</strong> <?= $d['nama_obat'] ?> (<?= $d['satuan'] ?>)</p>
        </div>
    </div>

    <!-- Detail Diagnosa & Resep -->
    <div style="display: flex; flex-direction: column; gap: 15px;">
        <div style="display: flex; gap: 20px;">
            <span style="width: 100px; font-weight: 700; color: #475569;">Keluhan :</span>
            <span><?= $d['keluhan'] ?></span>
        </div>
        <div style="display: flex; gap: 20px; align-items: center;">
            <span style="width: 100px; font-weight: 700; color: #475569;">Diagnosa :</span>
            <span class="badge badge-blue"><?= $d['diagnosa'] ?></span>
        </div>
        <div style="display: flex; gap: 20px;">
            <span style="width: 100px; font-weight: 700; color: #475569;">Tindakan :</span>
            <span><?= $d['tindakan'] ?></span>
        </div>
        <div style="display: flex; gap: 20px;">
            <span style="width: 100px; font-weight: 700; color: #475569;">Resep :</span>
            <span style="font-weight: 600; color: #1D4ED8;"><?= $d['resep'] ?></span>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>