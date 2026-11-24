<?php 
include 'koneksi.php';

// 1. Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM obat WHERE id_obat='$id'");
    header("Location: obat.php?pesan=hapus");
    exit();
}

// 2. Proses Simpan / Edit Data
if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_obat']);
    $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);
    $stok   = mysqli_real_escape_string($koneksi, $_POST['stok']);

    if (empty($_POST['id_obat'])) {
        mysqli_query($koneksi, "INSERT INTO obat (nama_obat, satuan, stok) VALUES ('$nama', '$satuan', '$stok')");
    } else {
        $id = mysqli_real_escape_string($koneksi, $_POST['id_obat']);
        mysqli_query($koneksi, "UPDATE obat SET nama_obat='$nama', satuan='$satuan', stok='$stok' WHERE id_obat='$id'");
    }
    header("Location: obat.php?pesan=simpan");
    exit();
}

include 'header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800;"><i class="fa-solid fa-pills" style="color: #1D4ED8;"></i> Data Obat</h2>
            <p style="font-size: 13px; color: #64748B;">Kelola data obat dan stok</p>
        </div>
        <button onclick="openModal()" class="btn-primary"><i class="fa-solid fa-plus"></i> Tambah Obat</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY id_obat DESC");
            while ($d = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><strong><?= htmlspecialchars($d['nama_obat']) ?></strong></td>
                <td><?= htmlspecialchars($d['satuan']) ?></td>
                <td><span class="badge badge-blue"><?= htmlspecialchars($d['stok']) ?></span></td>
                <td>
                    <button onclick='editData(<?= json_encode($d) ?>)' class="btn-icon btn-edit"><i class="fa-solid fa-pen"></i></button>
                    <a href="obat.php?hapus=<?= $d['id_obat'] ?>" onclick="return confirm('Hapus obat ini?')" class="btn-icon btn-delete"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Obat -->
<div id="modalObat" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; justify-content:center; align-items:center;">
    <div style="background:white; padding:30px; border-radius:20px; width:400px;">
        <h3 id="modalTitle" style="margin-bottom: 20px;">Tambah Obat</h3>
        <form method="POST" action="obat.php">
            <input type="hidden" name="id_obat" id="id_obat">
            <div class="form-group">
                <label>Nama Obat</label>
                <input type="text" name="nama_obat" id="nama_obat" class="form-control" placeholder="Contoh: Paracetamol" required>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" id="satuan" class="form-control" placeholder="Contoh: Tablet / Kapsul / Sirup" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" id="stok" class="form-control" required>
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
    document.getElementById('modalObat').style.display = 'flex'; 
    document.getElementById('modalTitle').innerText = 'Tambah Obat';
    document.getElementById('id_obat').value = ''; 
    document.getElementById('nama_obat').value = ''; 
    document.getElementById('satuan').value = ''; 
    document.getElementById('stok').value = ''; 
}
function closeModal() { document.getElementById('modalObat').style.display = 'none'; }
function editData(data) {
    openModal();
    document.getElementById('modalTitle').innerText = 'Edit Obat';
    document.getElementById('id_obat').value = data.id_obat;
    document.getElementById('nama_obat').value = data.nama_obat;
    document.getElementById('satuan').value = data.satuan;
    document.getElementById('stok').value = data.stok;
}
</script>

<?php include 'footer.php'; ?>