<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// 1. PROSES HAPUS DATA PASIEN
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    if (mysqli_query($koneksi, "DELETE FROM pasien WHERE id_pasien='$id'")) {
        echo "<script>window.location.href='pasien.php?pesan=hapus';</script>";
        exit();
    } else {
        die("Gagal Menghapus Data: " . mysqli_error($koneksi));
    }
}

// 2. PROSES SIMPAN / EDIT DATA PASIEN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_pasien'])) {
    $no_rm   = mysqli_real_escape_string($koneksi, $_POST['no_rm']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_pasien']);
    $jk      = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tgl     = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $id_pas  = $_POST['id_pasien'];

    if (empty($id_pas)) {
        $sql = "INSERT INTO pasien (no_rm, nama_pasien, jenis_kelamin, tanggal_lahir, alamat) 
                VALUES ('$no_rm', '$nama', '$jk', '$tgl', '$alamat')";
    } else {
        $id_pas = mysqli_real_escape_string($koneksi, $id_pas);
        $sql = "UPDATE pasien SET 
                    no_rm='$no_rm', 
                    nama_pasien='$nama', 
                    jenis_kelamin='$jk', 
                    tanggal_lahir='$tgl', 
                    alamat='$alamat' 
                WHERE id_pasien='$id_pas'";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>window.location.href='pasien.php?pesan=simpan';</script>";
        exit();
    } else {
        die("Gagal Menyimpan Data: " . mysqli_error($koneksi));
    }
}

include 'header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800;"><i class="fa-solid fa-user-group" style="color: #1D4ED8;"></i> Data Pasien</h2>
            <p style="font-size: 13px; color: #64748B;">Kelola data pasien dengan mudah</p>
        </div>
        <button onclick="openModal()" class="btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pasien</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY id_pasien DESC");
            if ($query && mysqli_num_rows($query) > 0) {
                while ($d = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><strong><?= htmlspecialchars($d['no_rm']) ?></strong></td>
                <td><?= htmlspecialchars($d['nama_pasien']) ?></td>
                <td><span class="badge badge-blue"><?= htmlspecialchars($d['jenis_kelamin']) ?></span></td>
                <td><?= date('d-m-Y', strtotime($d['tanggal_lahir'])) ?></td>
                <td><?= htmlspecialchars($d['alamat']) ?></td>
                <td>
                    <button onclick='editData(<?= json_encode($d) ?>)' class="btn-icon btn-edit"><i class="fa-solid fa-pen"></i></button>
                    <a href="pasien.php?hapus=<?= $d['id_pasien'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn-icon btn-delete"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data pasien.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Pasien -->
<div id="modalPasien" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; justify-content:center; align-items:center;">
    <div style="background:white; padding:30px; border-radius:20px; width:450px;">
        <h3 id="modalTitle" style="margin-bottom: 20px;">Tambah Pasien</h3>
        <form method="POST" action="pasien.php">
            <input type="hidden" name="id_pasien" id="id_pasien">
            <div class="form-group">
                <label>No. Rekam Medis (RM)</label>
                <input type="text" name="no_rm" id="no_rm" class="form-control" placeholder="RM001" required>
            </div>
            <div class="form-group">
                <label>Nama Pasien</label>
                <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeModal()" class="btn-secondary">Batal</button>
                <button type="submit" name="simpan_pasien" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalPasien').style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Tambah Pasien';
    document.getElementById('id_pasien').value = '';
    document.getElementById('no_rm').value = '';
    document.getElementById('nama_pasien').value = '';
    document.getElementById('tanggal_lahir').value = '';
    document.getElementById('alamat').value = '';
}
function closeModal() { 
    document.getElementById('modalPasien').style.display = 'none'; 
}
function editData(data) {
    openModal();
    document.getElementById('modalTitle').innerText = 'Edit Pasien';
    document.getElementById('id_pasien').value = data.id_pasien;
    document.getElementById('no_rm').value = data.no_rm;
    document.getElementById('nama_pasien').value = data.nama_pasien;
    document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
    document.getElementById('tanggal_lahir').value = data.tanggal_lahir;
    document.getElementById('alamat').value = data.alamat;
}
</script>

<?php include 'footer.php'; ?>