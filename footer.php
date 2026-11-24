</div> <!-- End Content -->
    </div> <!-- End Main Wrapper -->

    <?php if (isset($_GET['pesan'])): 
        $isError = ($_GET['pesan'] == 'gagal_hapus');
        $bgColor = $isError ? '#EF4444' : '#10B981';
        $shadowColor = $isError ? 'rgba(239, 68, 68, 0.35)' : 'rgba(16, 185, 129, 0.35)';
        $icon = $isError ? 'fa-circle-exclamation' : 'fa-circle-check';
    ?>
    <div id="toastNotif" style="position: fixed; top: 24px; right: 24px; background: <?= $bgColor ?>; color: white; padding: 16px 24px; border-radius: 14px; box-shadow: 0 10px 25px <?= $shadowColor ?>; display: flex; align-items: center; gap: 12px; z-index: 9999; font-weight: 600; font-size: 14px; animation: slideIn 0.4s ease;">
        <i class="fa-solid <?= $icon ?>" style="font-size: 20px;"></i>
        <span>
            <?php 
                if ($_GET['pesan'] == 'simpan') echo 'Data berhasil disimpan!';
                elseif ($_GET['pesan'] == 'hapus') echo 'Data berhasil dihapus!';
                elseif ($_GET['pesan'] == 'gagal_hapus') echo 'Gagal menghapus! Data masih terhubung di Rekam Medis.';
            ?>
        </span>
    </div>

    <style>
        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>

    <script>
        setTimeout(function() {
            var toast = document.getElementById('toastNotif');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 3500);

        window.history.replaceState(null, null, window.location.pathname);
    </script>
    <?php endif; ?>
</body>
</html>