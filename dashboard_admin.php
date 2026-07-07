<?php 
include 'koneksi.php';
if($_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }

// Proses Update Status (CRUD: Update)
if(isset($_POST['update_status'])) {
    $id_laporan = $_POST['id_laporan'];
    $status_baru = $_POST['status_baru'];
    
    mysqli_query($conn, "UPDATE laporan SET status='$status_baru' WHERE id='$id_laporan'");
    echo "<script>alert('Status laporan berhasil diperbarui!'); window.location='dashboard_admin.php';</script>";
}

// Proses Hapus Laporan (CRUD: Delete)
if(isset($_POST['hapus_laporan'])) {
    $id_laporan = $_POST['id_laporan'];
    
    // Cari nama file foto lama agar space penyimpanan lokal/hosting tidak bengkak
    $cari_foto = mysqli_query($conn, "SELECT foto FROM laporan WHERE id='$id_laporan'");
    $data_foto = mysqli_fetch_assoc($cari_foto);
    if($data_foto && !empty($data_foto['foto']) && file_exists("uploads/" . $data_foto['foto'])) {
        unlink("uploads/" . $data_foto['foto']);
    }

    mysqli_query($conn, "DELETE FROM laporan WHERE id='$id_laporan'");
    echo "<script>alert('Laporan berhasil dihapus dari sistem!'); window.location='dashboard_admin.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Admin Panel - Pengaduan</title>
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 mt-3">
            <h1 class="fw-bold">🛠️ DASHBOARD ADMIN PENGELOLA</h1>
            <a href="logout.php" class="btn btn-danger brutal-btn bg-danger text-white">Keluar</a>
        </div>

        <div class="card p-4 brutal-card" style="background-color: #ff7676;">
            <h3 class="fw-bold mb-4 text-white">DAFTAR MASUK LAPORAN</h3>
            <div class="table-responsive">
                <table class="table table-bordered border-dark table-striped bg-white m-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Pelapor</th>
                            <th>Fasilitas & Detail</th>
                            <th>Foto Bukti</th>
                            <th>Status Sekarang</th>
                            <th>Aksi Pengelolaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT laporan.*, users.username FROM laporan JOIN users ON laporan.user_id = users.id ORDER BY laporan.id DESC";
                        $res = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_assoc($res)) {
                            ?>
                            <tr>
                                <td><b><?= $row['username']; ?></b></td>
                                <td>
                                    <h5><b><?= $row['judul']; ?></b></h5>
                                    <p><?= $row['deskripsi']; ?></p>
                                    <small class="text-muted"><?= $row['tanggal']; ?></small>
                                </td>
                                <td>
                                    <img src="uploads/<?= $row['foto']; ?>" width="120" style="border: 3px solid #000;">
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark border border-dark fw-bold"><?= $row['status']; ?></span>
                                </td>
                                <td>
                                    <form action="" method="POST" class="d-flex gap-2 mb-2">
                                        <input type="hidden" name="id_laporan" value="<?= $row['id']; ?>">
                                        <select name="status_baru" class="form-select brutal-input form-select-sm">
                                            <option value="Pending" <?= $row['status']=='Pending'?'selected':''; ?>>Pending</option>
                                            <option value="Diterima" <?= $row['status']=='Diterima'?'selected':''; ?>>Terima & Proses</option>
                                            <option value="Ditolak" <?= $row['status']=='Ditolak'?'selected':''; ?>>Tolak</option>
                                            <option value="Selesai" <?= $row['status']=='Selesai'?'selected':''; ?>>Selesai</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm brutal-btn bg-info">Update</button>
                                    </form>

                                    <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen laporan ini beserta berkas gambarnya?');">
                                        <input type="hidden" name="id_laporan" value="<?= $row['id']; ?>">
                                        <button type="submit" name="hapus_laporan" class="btn btn-sm btn-danger w-100 brutal-btn bg-danger text-white">Hapus Laporan</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>