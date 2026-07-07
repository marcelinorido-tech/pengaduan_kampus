<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password sama
    if ($password !== $confirm_password) {
        $error_msg = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah terpakai
        $check_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error_msg = "Username sudah terdaftar! Gunakan yang lain.";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Default role untuk pendaftaran mandiri adalah mahasiswa
            $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', 'mahasiswa')";
            
            if (mysqli_query($conn, $query)) {
                $success_msg = "Registrasi Berhasil! Silakan login.";
            } else {
                $error_msg = "Gagal mendaftar, coba lagi nanti.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Daftar Akun - Neo Brutalism</title>
</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card p-4 brutal-card" style="width: 400px; background-color: #38bdf8;"> <h2 class="text-center fw-bold mb-4">DAFTAR AKUN</h2>
        
        <?php if(isset($error_msg)) echo "<p class='text-danger fw-bold'>⚠️ $error_msg</p>"; ?>
        <?php if(isset($success_msg)) echo "<p class='text-dark fw-bold'>✅ $success_msg</p>"; ?>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Username Baru</label>
                <input type="text" name="username" class="form-control brutal-input" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control brutal-input" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control brutal-input" required>
            </div>
            <button type="submit" name="register" class="btn brutal-btn w-100 mt-2" style="background-color: #facc15;">DAFTAR SEKARANG 📝</button>
            
            <p class="text-center fw-bold mt-3 mb-0">
                Sudah punya akun? <a href="login.php" class="text-dark text-decoration-underline">Login di sini</a>
            </p>
        </form>
    </div>
</body>
</html>