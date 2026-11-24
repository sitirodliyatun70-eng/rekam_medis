<?php
include 'koneksi.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Rekam Medis</title>
    <!-- FontAwesome & Google Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #F0F4F9; color: #1E293B; display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { width: 250px; background: linear-gradient(180deg, #0F3875 0%, #0A2550 100%); color: #fff; display: flex; flex-direction: column; padding: 20px 15px; shrink: 0; }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding: 0 10px; }
        .brand-icon { width: 42px; height: 42px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #38BDF8; }
        .brand-text h2 { font-size: 18px; font-weight: 800; line-height: 1.2; }
        .brand-text p { font-size: 11px; opacity: 0.7; }
        
        .nav-menu { list-style: none; display: flex; flex-direction: column; gap: 8px; flex-grow: 1; }
        .nav-link { display: flex; align-items: center; gap: 14px; padding: 12px 16px; color: #94A3B8; text-decoration: none; border-radius: 12px; font-size: 14px; font-weight: 600; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background: #1D4ED8; color: #FFFFFF; box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3); }
        .logout-btn { margin-top: auto; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 600; }
        
        /* Main Content */
        .main-wrapper { flex-grow: 1; display: flex; flex-direction: column; overflow-x: hidden; }
        .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 30px; background: transparent; }
        .menu-toggle { font-size: 20px; color: #0F3875; cursor: pointer; }
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .notification { position: relative; font-size: 18px; color: #64748B; cursor: pointer; }
        .badge-dot { position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background: #EF4444; border-radius: 50%; }
        .user-profile { display: flex; align-items: center; gap: 10px; background: #fff; padding: 6px 14px; border-radius: 30px; border: 1px solid #E2E8F0; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: #3B82F6; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        .content { padding: 0 30px 30px 30px; }

        /* General UI Elements */
        .card { background: #FFFFFF; border-radius: 20px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 24px; border: 1px solid #F1F5F9; }
        .btn-primary { background: #1D4ED8; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .btn-primary:hover { background: #1E40AF; }
        .btn-secondary { background: #E2E8F0; color: #334155; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; }
        .btn-icon { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; color: white; text-decoration: none; border: none; cursor: pointer; font-size: 13px; }
        .btn-edit { background: #3B82F6; }
        .btn-delete { background: #EF4444; }
        .btn-detail { background: #0EA5E9; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px 16px; background: #F8FAFC; color: #475569; font-size: 13px; font-weight: 700; border-bottom: 2px solid #E2E8F0; }
        td { padding: 14px 16px; border-bottom: 1px solid #F1F5F9; font-size: 14px; color: #334155; }
        tr:hover td { background: #F8FAFC; }

        /* Form Controls */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 14px; outline: none; }
        .form-control:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
        .badge-blue { background: #DBEAFE; color: #1E40AF; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <div class="brand-text">
                <h2>Rekam Medis</h2>
                <p>Sehat Bersama, Hidup Lebih Baik</p>
            </div>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link <?= $current_page=='index.php'?'active':'' ?>"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="pasien.php" class="nav-link <?= $current_page=='pasien.php'?'active':'' ?>"><i class="fa-solid fa-user-group"></i> Pasien</a></li>
            <li><a href="dokter.php" class="nav-link <?= $current_page=='dokter.php'?'active':'' ?>"><i class="fa-solid fa-user-doctor"></i> Dokter</a></li>
            <li><a href="obat.php" class="nav-link <?= $current_page=='obat.php'?'active':'' ?>"><i class="fa-solid fa-pills"></i> Obat</a></li>
            <li><a href="rekam_medis.php" class="nav-link <?= $current_page=='rekam_medis.php'||$current_page=='detail_rekam.php'?'active':'' ?>"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a></li>
            <li><a href="riwayat.php" class="nav-link <?= $current_page=='riwayat.php'?'active':'' ?>"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pemeriksaan</a></li>
        </ul>
        <a href="#" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <div class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="topbar-right">
                <div class="notification"><i class="fa-regular fa-bell"></i><span class="badge-dot"></span></div>
                <div class="user-profile">
                    <div class="user-avatar">A</div>
                    <span style="font-size: 14px; font-weight: 600;">Admin <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i></span>
                </div>
            </div>
        </div>
        <div class="content">