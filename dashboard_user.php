<?php 
include 'koneksi.php';
if($_SESSION['role'] != 'mahasiswa') { header("Location: login.php"); exit; }

// Proses Simpan Laporan (CRUD: Create)
if(isset($_POST['kirim_laporan'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $user_id = $_SESSION['user_id'];
    
    // Upload Gambar
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $path = "uploads/".$foto;
    
    if(move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO laporan (user_id, judul, deskripsi, foto) VALUES ('$user_id', '$judul', '$deskripsi', '$foto')";
        mysqli_query($conn, $query);
        echo "<script>alert('Laporan berhasil dikirim!'); window.location='dashboard_user.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Sistem Pengaduan Kampus</title>
</head>
<body class="p-4">
    <div class="container m-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold">📢 AJUKAN PENGADUAN</h1>
            <a href="logout.php" class="btn btn-danger brutal-btn bg-danger text-white">Keluar</a>
        </div>
        
        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card p-4 brutal-card">
                    <form id="formPengaduan" action="" method="POST" enctype="multipart/form-data" onsubmit="return validasiForm()">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fasilitas yang Dilaporkan</label>
                            <input type="text" name="judul" id="judul" class="form-control brutal-input">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Kerusakan</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control brutal-input" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bukti Foto</label>
                            <input type="file" name="foto" id="foto" class="form-control brutal-input" accept="image/*">
                        </div>
                        <button type="submit" name="kirim_laporan" class="btn brutal-btn w-100">KIRIM LAPORAN 🚀</button>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card p-4 brutal-card" style="background-color: #a3e635;">
                    <h4 class="fw-bold mb-3">RIWAYAT LAPORAN ANDA</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered border-dark bg-white">
                            <thead>
                                <tr class="table-dark">
                                    <th>Fasilitas</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $uid = $_SESSION['user_id'];
                                $res = mysqli_query($conn, "SELECT * FROM laporan WHERE user_id='$uid' ORDER BY id DESC");
                                while($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>
                                        <td><b>{$row['judul']}</b><br><small>{$row['deskripsi']}</small></td>
                                        <td><span class='badge bg-dark text-warning'>{$row['status']}</span></td>
                                        <td><img src='uploads/{$row['foto']}' width='80' style='border: 2px solid #000;'></td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function validasiForm() {
        let judul = document.getElementById('judul').value;
        let deskripsi = document.getElementById('deskripsi').value;
        let foto = document.getElementById('foto').value;
        
        if(judul == "" || deskripsi == "" || foto == "") {
            alert("Semua kolom (termasuk foto bukti) wajib diisi!");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>