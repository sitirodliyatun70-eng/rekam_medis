<?php 
include 'header.php';

// Hitung Agregat Data SQL COUNT()
$total_pasien = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pasien"))['total'];
$total_dokter = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM dokter"))['total'];
$total_obat   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM obat"))['total'];
$total_rekam  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM rekam_medis"))['total'];
?>

<style>
    .banner { background: linear-gradient(135deg, #E0F2FE 0%, #BAE6FD 100%); border-radius: 24px; padding: 35px; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden; margin-bottom: 24px; }
    .banner-text h1 { font-size: 26px; color: #0C4A6E; font-weight: 800; margin-bottom: 8px; }
    .banner-text p { color: #0369A1; font-size: 15px; margin-bottom: 20px; }
    .banner-img { font-size: 110px; color: #0284C7; opacity: 0.8; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card { background: white; padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 16px; border: 1px solid #F1F5F9; }
    .stat-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
    .stat-info h3 { font-size: 24px; font-weight: 800; color: #0F172A; }
    .stat-info p { font-size: 13px; color: #64748B; font-weight: 600; }

    .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .info-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 18px; padding: 20px; display: flex; align-items: center; gap: 15px; color: #1E40AF; }
    .quote-box { background: white; border-radius: 18px; padding: 20px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #F1F5F9; font-style: italic; color: #334155; font-weight: 600; }
</style>

<!-- Banner Hero -->
<div class="banner">
    <div class="banner-text">
        <p style="text-transform: uppercase; letter-spacing: 1px; font-size: 12px; font-weight: 700; color: #0284C7; margin-bottom: 4px;">Selamat Datang di</p>
        <h1>Aplikasi Rekam Medis</h1>
        <p>Kelola data kesehatan dengan mudah, cepat dan aman.</p>
        <a href="rekam_medis.php" class="btn-primary">Lihat Data Lengkap <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="banner-img">
        <i class="fa-solid fa-notes-medical"></i>
    </div>
</div>

<!-- Grid Statistics Agregat -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #E0F2FE; color: #0284C7;"><i class="fa-solid fa-user-group"></i></div>
        <div class="stat-info">
            <h3><?= $total_pasien ?></h3>
            <p>Total Pasien</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #DCFCE7; color: #16A34A;"><i class="fa-solid fa-user-doctor"></i></div>
        <div class="stat-info">
            <h3><?= $total_dokter ?></h3>
            <p>Total Dokter</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #F3E8FF; color: #9333EA;"><i class="fa-solid fa-pills"></i></div>
        <div class="stat-info">
            <h3><?= $total_obat ?></h3>
            <p>Total Obat</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #FFE4E6; color: #E11D48;"><i class="fa-solid fa-file-medical"></i></div>
        <div class="stat-info">
            <h3><?= $total_rekam ?></h3>
            <p>Total Rekam Medis</p>
        </div>
    </div>
</div>

<!-- Bottom Info -->
<div class="bottom-grid">
    <div class="info-box">
        <i class="fa-regular fa-calendar-days" style="font-size: 30px;"></i>
        <div>
            <h4 style="font-size: 14px; font-weight: 700;">Informasi Hari Ini</h4>
            <p style="font-size: 13px; opacity: 0.8;"><?= date('l, d F Y') ?> | <?= date('H:i') ?> WIB</p>
        </div>
    </div>
    <div class="quote-box">
        <span>"Kesehatan adalah anugerah paling berharga."</span>
        <i class="fa-solid fa-heart-pulse" style="color: #3B82F6; font-size: 24px;"></i>
    </div>
</div>

<?php include 'footer.php'; ?>