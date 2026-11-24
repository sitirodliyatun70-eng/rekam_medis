<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// 1. PROSES HAPUS REKAM MEDIS
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    if (mysqli_query($koneksi, "DELETE FROM rekam_medis WHERE id_rekam='$id'")) {
        echo "<script>window.location.href='rekam_medis.php?pesan=hapus';</script>";
        exit();
    } else {
        die("Gagal Menghapus Rekam Medis: " . mysqli_error($koneksi));
    }
}

// 2. PROSES SIMPAN / EDIT REKAM MEDIS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_rekam'])) {
    $id_rekam  = $_POST['id_rekam'];
    $id_pasien = mysqli_real_escape_string($koneksi, $_POST['id_pasien']);
    $id_dokter = mysqli_real_escape_string($koneksi, $_POST['id_dokter']);
    $id_obat   = mysqli_real_escape_string($koneksi, $_POST['id_obat']);
    $tgl       = mysqli_real_escape_string($koneksi, $_POST['tanggal_periksa']);
    $keluhan   = mysqli_real_escape_string($koneksi, $_POST['keluhan']);
    $diagnosa  = mysqli_real_escape_string($koneksi, $_POST['diagnosa']);
    $tindakan  = mysqli_real_escape_string($koneksi, $_POST['tindakan']);
    $resep     = mysqli_real_escape_string($koneksi, $_POST['resep']);

    if (empty($id_rekam)) {
        // Tambah Data Baru
        $sql = "INSERT INTO rekam_medis 
                (id_pasien, id_dokter, id_obat, tanggal_periksa, keluhan, diagnosa, tindakan, resep) 
                VALUES 
                ('$id_pasien', '$id_dokter', '$id_obat', '$tgl', '$keluhan', '$diagnosa', '$tindakan', '$resep')";
    } else {
        // Update / Edit Data Lama
        $id_rekam = mysqli_real_escape_string($koneksi, $id_rekam);
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
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>window.location.href='rekam_medis.php?pesan=simpan';</script>";
        exit();
    } else {
        die("Gagal Menyimpan Rekam Medis: " . mysqli_error($koneksi));
    }
}

include 'header.php';
?>

<div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 20px;">
    <!-- Form Tambah / Edit Rekam Medis (Kiri) -->
    <div class="card">
        <h3 id="formTitle" style="font-size: 18px; font-weight: 800; margin-bottom: 5px;">
            <i class="fa-solid fa-stethoscope" style="color: #1D4ED8;"></i> Form Pemeriksaan
        </h3>
        <p id="formSubtitle" style="font-size: 12px; color: #64748B; margin-bottom: 20px;">Input data rekam medis pasien</p>
        
        <form method="POST" action="rekam_medis.php" id="formRekam">
            <input type="hidden" name="id_rekam" id="id_rekam">

            <div class="form-group">
                <label>Pasien <span style="color:red;">*</span></label>
                <select name="id_pasien" id="id_pasien" class="form-control" required>
                    <option value="">-- Pilih Pasien --</option>
                    <?php
                    $p = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY nama_pasien ASC");
                    while ($pas = mysqli_fetch_assoc($p)) {
                        echo "<option value='".$pas['id_pasien']."'>".$pas['nama_pasien']." (".$pas['no_rm'].")</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Dokter <span style="color:red;">*</span></label>
                <select name="id_dokter" id="id_dokter" class="form-control" required>
                    <option value="">-- Pilih Dokter --</option>
                    <?php
                    $d = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY nama_dokter ASC");
                    while ($dok = mysqli_fetch_assoc($d)) {
                        echo "<option value='".$dok['id_dokter']."'>".$dok['nama_dokter']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Obat / Resep <span style="color:red;">*</span></label>
                <select name="id_obat" id="id_obat" class="form-control" required>
                    <option value="">-- Pilih Obat --</option>
                    <?php
                    $o = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY nama_obat ASC");
                    while ($ob = mysqli_fetch_assoc($o)) {
                        echo "<option value='".$ob['id_obat']."'>".$ob['nama_obat']." (".$ob['satuan'].")</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Periksa <span style="color:red;">*</span></label>
                <input type="date" name="tanggal_periksa" id="tanggal_periksa" value="<?= date('Y-m-d') ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Keluhan</label>
                <textarea name="keluhan" id="keluhan" class="form-control" rows="2" placeholder="Contoh: Demam dan batuk"></textarea>
            </div>

            <div class="form-group">
                <label>Diagnosa</label>
                <input type="text" name="diagnosa" id="diagnosa" class="form-control" placeholder="Contoh: Influenza, ISPA">
            </div>

            <div class="form-group">
                <label>Tindakan</label>
                <input type="text" name="tindakan" id="tindakan" class="form-control" placeholder="Contoh: Istirahat cukup dan minum obat">
            </div>

            <div class="form-group">
                <label>Aturan Resep</label>
                <input type="text" name="resep" id="resep" class="form-control" placeholder="Contoh: 3 x 1 tablet sesudah makan">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="simpan_rekam" id="btnSubmit" class="btn-primary" style="flex: 1;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
                <button type="button" onclick="resetForm()" class="btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Rekam Medis (Kanan) -->
    <div class="card">
        <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 20px;">Daftar Rekam Medis</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Diagnosa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sql = "SELECT rekam_medis.*, pasien.nama_pasien, dokter.nama_dokter 
                        FROM rekam_medis 
                        JOIN pasien ON rekam_medis.id_pasien = pasien.id_pasien 
                        JOIN dokter ON rekam_medis.id_dokter = dokter.id_dokter 
                        ORDER BY rekam_medis.id_rekam DESC";
                $query = mysqli_query($koneksi, $sql);
                if ($query && mysqli_num_rows($query) > 0) {
                    while ($rm = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($rm['tanggal_periksa'])) ?></td>
                    <td><strong><?= htmlspecialchars($rm['nama_pasien']) ?></strong></td>
                    <td><?= htmlspecialchars($rm['nama_dokter']) ?></td>
                    <td><?= htmlspecialchars($rm['diagnosa']) ?></td>
                    <td style="display: flex; gap: 5px;">
                        <a href="detail_rekam.php?id=<?= $rm['id_rekam'] ?>" class="btn-icon btn-detail" title="Detail"><i class="fa-solid fa-eye"></i></a>
                        <button type="button" onclick='editRekam(<?= json_encode($rm) ?>)' class="btn-icon btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                        <a href="rekam_medis.php?hapus=<?= $rm['id_rekam'] ?>" onclick="return confirm('Hapus catatan ini?')" class="btn-icon btn-delete" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data rekam medis.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editRekam(data) {
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square" style="color: #3B82F6;"></i> Edit Pemeriksaan';
    document.getElementById('formSubtitle').innerText = 'Perbarui data rekam medis pasien';
    document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Update Data';

    document.getElementById('id_rekam').value = data.id_rekam;
    document.getElementById('id_pasien').value = data.id_pasien;
    document.getElementById('id_dokter').value = data.id_dokter;
    document.getElementById('id_obat').value = data.id_obat;
    document.getElementById('tanggal_periksa').value = data.tanggal_periksa;
    document.getElementById('keluhan').value = data.keluhan;
    document.getElementById('diagnosa').value = data.diagnosa;
    document.getElementById('tindakan').value = data.tindakan;
    document.getElementById('resep').value = data.resep;

    // Scroll halus ke atas agar form langsung terlihat di layar kecil
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('formRekam').reset();
    document.getElementById('id_rekam').value = '';
    document.getElementById('tanggal_periksa').value = '<?= date('Y-m-d') ?>';
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-stethoscope" style="color: #1D4ED8;"></i> Form Pemeriksaan';
    document.getElementById('formSubtitle').innerText = 'Input data rekam medis pasien';
    document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan';
}
</script>

<?php include 'footer.php'; ?>