<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// 1. PROSES HAPUS RIWAYAT PEMERIKSAAN
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    if (mysqli_query($koneksi, "DELETE FROM rekam_medis WHERE id_rekam='$id'")) {
        echo "<script>window.location.href='riwayat.php?pesan=hapus';</script>";
        exit();
    } else {
        die("Gagal Menghapus Data: " . mysqli_error($koneksi));
    }
}

// 2. PROSES UPDATE / EDIT RIWAYAT PEMERIKSAAN (DARI MODAL)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_riwayat'])) {
    $id_rekam  = mysqli_real_escape_string($koneksi, $_POST['id_rekam']);
    $id_pasien = mysqli_real_escape_string($koneksi, $_POST['id_pasien']);
    $id_dokter = mysqli_real_escape_string($koneksi, $_POST['id_dokter']);
    $id_obat   = mysqli_real_escape_string($koneksi, $_POST['id_obat']);
    $tgl       = mysqli_real_escape_string($koneksi, $_POST['tanggal_periksa']);
    $keluhan   = mysqli_real_escape_string($koneksi, $_POST['keluhan']);
    $diagnosa  = mysqli_real_escape_string($koneksi, $_POST['diagnosa']);
    $tindakan  = mysqli_real_escape_string($koneksi, $_POST['tindakan']);
    $resep     = mysqli_real_escape_string($koneksi, $_POST['resep']);

    $sql = "UPDATE rekam_medis SET 
                id_pasien='$id_pasien', 
                id_dokter='$id_dokter', 
                id_obat='$id_obat', 
                tanggal_periksa='$tgl', 
                keluhan='$keluhan', 
                diagnosa='$diagnosa', 
                tindakan='$tindakan', 
                resep='$resep' 
            WHERE id_rekam='$id_rekam'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>window.location.href='riwayat.php?pesan=simpan';</script>";
        exit();
    } else {
        die("Gagal Memperbarui Data: " . mysqli_error($koneksi));
    }
}

include 'header.php';
?>

<h2 style="font-size: 22px; font-weight: 800; color: #1D4ED8; margin-bottom: 16px;">Riwayat Pemeriksaan</h2>

<div class="card">
    <p style="font-size: 13px; color: #475569; margin-bottom: 16px;">
        Data di bawah ini ditarik menggunakan <strong>SQL JOIN</strong> dari 4 tabel.
    </p>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #E0F2FE;">
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">No</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Tgl</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Pasien</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Dokter</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Obat</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Keluhan</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Diagnosa</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Tindakan</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px;">Resep</th>
                    <th style="color: #0369A1; padding: 12px; font-size: 13px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // SQL JOIN 4 Tabel: rekam_medis, pasien, dokter, obat
                $sql = "SELECT rekam_medis.*, pasien.nama_pasien, dokter.nama_dokter, obat.nama_obat 
                        FROM rekam_medis 
                        JOIN pasien ON rekam_medis.id_pasien = pasien.id_pasien 
                        JOIN dokter ON rekam_medis.id_dokter = dokter.id_dokter 
                        JOIN obat ON rekam_medis.id_obat = obat.id_obat 
                        ORDER BY rekam_medis.id_rekam DESC";
                $query = mysqli_query($koneksi, $sql);
                if ($query && mysqli_num_rows($query) > 0) {
                    while ($rm = mysqli_fetch_assoc($query)) {
                        $rowBg = ($no % 2 == 0) ? '#FFFFFF' : '#F8FAFC';
                ?>
                <tr style="background: <?= $rowBg ?>; border-bottom: 1px solid #E2E8F0;">
                    <td style="padding: 14px 12px; font-size: 13px;"><?= $no++ ?></td>
                    <td style="padding: 14px 12px; font-size: 13px; white-space: nowrap;"><?= htmlspecialchars($rm['tanggal_periksa']) ?></td>
                    <td style="padding: 14px 12px; font-size: 13px; font-weight: 600;"><?= htmlspecialchars($rm['nama_pasien']) ?></td>
                    <td style="padding: 14px 12px; font-size: 13px;"><?= htmlspecialchars($rm['nama_dokter']) ?></td>
                    <td style="padding: 14px 12px; font-size: 13px;"><?= htmlspecialchars($rm['nama_obat']) ?></td>
                    <td style="padding: 14px 12px; font-size: 13px;"><?= htmlspecialchars($rm['keluhan']) ?></td>
                    <td style="padding: 14px 12px; font-size: 13px;"><?= htmlspecialchars($rm['diagnosa']) ?></td>
                    <td style="padding: 14px 12px; font-size: 13px;"><?= htmlspecialchars($rm['tindakan']) ?></td>
                    <td style="padding: 14px 12px; font-size: 13px;"><?= htmlspecialchars($rm['resep']) ?></td>
                    <td style="padding: 14px 12px; text-align: center;">
                        <div style="display: flex; flex-direction: column; gap: 6px; align-items: center;">
                            <button type="button" onclick='openEditModal(<?= json_encode($rm) ?>)' style="background: #38BDF8; color: white; border: none; padding: 6px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; width: 76px;">
                                Edit
                            </button>
                            <a href="riwayat.php?hapus=<?= $rm['id_rekam'] ?>" onclick="return confirm('Yakin ingin menghapus riwayat pemeriksaan ini?')" style="background: #1E3A8A; color: white; text-decoration: none; padding: 6px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; display: inline-block; width: 76px; text-align: center;">
                                Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='10' style='text-align:center; padding: 20px; color: #64748B;'>Belum ada data riwayat pemeriksaan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit Riwayat Pemeriksaan -->
<div id="modalEditRiwayat" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; justify-content:center; align-items:center;">
    <div style="background:white; padding:28px; border-radius:20px; width:500px; max-height: 90vh; overflow-y: auto;">
        <h3 style="margin-bottom: 18px; font-weight: 800; color: #1D4ED8;">Edit Riwayat Pemeriksaan</h3>
        <form method="POST" action="riwayat.php">
            <input type="hidden" name="id_rekam" id="edit_id_rekam">
            
            <div class="form-group">
                <label>Pasien</label>
                <select name="id_pasien" id="edit_id_pasien" class="form-control" required>
                    <?php
                    $p = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY nama_pasien ASC");
                    while ($pas = mysqli_fetch_assoc($p)) {
                        echo "<option value='".$pas['id_pasien']."'>".$pas['nama_pasien']." (".$pas['no_rm'].")</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Dokter</label>
                <select name="id_dokter" id="edit_id_dokter" class="form-control" required>
                    <?php
                    $d = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY nama_dokter ASC");
                    while ($dok = mysqli_fetch_assoc($d)) {
                        echo "<option value='".$dok['id_dokter']."'>".$dok['nama_dokter']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Obat</label>
                <select name="id_obat" id="edit_id_obat" class="form-control" required>
                    <?php
                    $o = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY nama_obat ASC");
                    while ($ob = mysqli_fetch_assoc($o)) {
                        echo "<option value='".$ob['id_obat']."'>".$ob['nama_obat']." (".$ob['satuan'].")</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Periksa</label>
                <input type="date" name="tanggal_periksa" id="edit_tanggal_periksa" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Keluhan</label>
                <input type="text" name="keluhan" id="edit_keluhan" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Diagnosa</label>
                <input type="text" name="diagnosa" id="edit_diagnosa" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tindakan</label>
                <input type="text" name="tindakan" id="edit_tindakan" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Resep</label>
                <input type="text" name="resep" id="edit_resep" class="form-control" required>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeEditModal()" class="btn-secondary">Batal</button>
                <button type="submit" name="update_riwayat" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('modalEditRiwayat').style.display = 'flex';
    document.getElementById('edit_id_rekam').value = data.id_rekam;
    document.getElementById('edit_id_pasien').value = data.id_pasien;
    document.getElementById('edit_id_dokter').value = data.id_dokter;
    document.getElementById('edit_id_obat').value = data.id_obat;
    document.getElementById('edit_tanggal_periksa').value = data.tanggal_periksa;
    document.getElementById('edit_keluhan').value = data.keluhan;
    document.getElementById('edit_diagnosa').value = data.diagnosa;
    document.getElementById('edit_tindakan').value = data.tindakan;
    document.getElementById('edit_resep').value = data.resep;
}
function closeEditModal() {
    document.getElementById('modalEditRiwayat').style.display = 'none';
}
</script>

<?php include 'footer.php'; ?>