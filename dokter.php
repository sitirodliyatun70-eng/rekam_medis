<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// 1. Proses Hapus Data Dokter
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    try {
        if (mysqli_query($koneksi, "DELETE FROM dokter WHERE id_dokter='$id'")) {
            echo "<script>window.location.href='dokter.php?pesan=hapus';</script>";
            exit();
        } else {
            echo "<script>window.location.href='dokter.php?pesan=gagal_hapus';</script>";
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Jika ditolak karena Foreign Key (dokter masih ada di riwayat rekam medis)
        echo "<script>window.location.href='dokter.php?pesan=gagal_hapus';</script>";
        exit();
    }
}

// 2. Proses Simpan / Edit Data Dokter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $nama         = mysqli_real_escape_string($koneksi, $_POST['nama_dokter']);
    $spesialisasi = mysqli_real_escape_string($koneksi, $_POST['spesialisasi']);

    if (empty($_POST['id_dokter'])) {
        $sql = "INSERT INTO dokter (nama_dokter, spesialisasi) VALUES ('$nama', '$spesialisasi')";
    } else {
        $id = mysqli_real_escape_string($koneksi, $_POST['id_dokter']);
        $sql = "UPDATE dokter SET nama_dokter='$nama', spesialisasi='$spesialisasi' WHERE id_dokter='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>window.location.href='dokter.php?pesan=simpan';</script>";
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
            <h2 style="font-size: 20px; font-weight: 800;"><i class="fa-solid fa-user-doctor" style="color: #1D4ED8;"></i> Data Dokter</h2>
            <p style="font-size: 13px; color: #64748B;">Kelola data dokter dan spesialisasinya</p>
        </div>
        <button onclick="openModal()" class="btn-primary"><i class="fa-solid fa-plus"></i> Tambah Dokter</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Dokter</th>
                <th>Spesialisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY id_dokter DESC");
            if ($query && mysqli_num_rows($query) > 0) {
                while ($d = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><strong><?= htmlspecialchars($d['nama_dokter']) ?></strong></td>
                <td><?= htmlspecialchars($d['spesialisasi']) ?></td>
                <td>
                    <button onclick='editData(<?= json_encode($d) ?>)' class="btn-icon btn-edit"><i class="fa-solid fa-pen"></i></button>
                    <a href="dokter.php?hapus=<?= $d['id_dokter'] ?>" onclick="return confirm('Hapus dokter ini?')" class="btn-icon btn-delete"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data dokter.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Dokter -->
<div id="modalDokter" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; justify-content:center; align-items:center;">
    <div style="background:white; padding:30px; border-radius:20px; width:400px;">
        <h3 id="modalTitle" style="margin-bottom: 20px;">Tambah Dokter</h3>
        <form method="POST" action="dokter.php">
            <input type="hidden" name="id_dokter" id="id_dokter">
            <div class="form-group">
                <label>Nama Dokter</label>
                <input type="text" name="nama_dokter" id="nama_dokter" class="form-control" placeholder="Contoh: dr. Andi Pratama" required>
            </div>
            <div class="form-group">
                <label>Spesialisasi</label>
                <input type="text" name="spesialisasi" id="spesialisasi" class="form-control" placeholder="Contoh: Umum / Anak" required>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeModal()" class="btn-secondary">Batal</button>
                <button type="submit" name="simpan" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() { 
    document.getElementById('modalDokter').style.display = 'flex'; 
    document.getElementById('modalTitle').innerText = 'Tambah Dokter';
    document.getElementById('id_dokter').value = ''; 
    document.getElementById('nama_dokter').value = ''; 
    document.getElementById('spesialisasi').value = ''; 
}
function closeModal() { document.getElementById('modalDokter').style.display = 'none'; }
function editData(data) {
    openModal();
    document.getElementById('modalTitle').innerText = 'Edit Dokter';
    document.getElementById('id_dokter').value = data.id_dokter;
    document.getElementById('nama_dokter').value = data.nama_dokter;
    document.getElementById('spesialisasi').value = data.spesialisasi;
}
</script>

<?php include 'footer.php'; ?>