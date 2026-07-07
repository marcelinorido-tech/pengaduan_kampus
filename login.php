<?php
include 'koneksi.php';

// --- AUTO REGISTER / REPAIR ACCOUNT DEMO ---
// Skrip ini akan otomatis memastikan akun mahasiswa1 dan admin_kampus terdaftar dengan benar
$check_mhs = mysqli_query($conn, "SELECT * FROM users WHERE username='mahasiswa1'");
if(mysqli_num_rows($check_mhs) == 0) {
    $pass_mhs = password_hash('mhs123', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('mahasiswa1', '$pass_mhs', 'mahasiswa')");
} else {
    // Jika sudah ada, kita paksa update password-nya agar pasti mhs123
    $pass_mhs = password_hash('mhs123', PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$pass_mhs' WHERE username='mahasiswa1'");
}

$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE username='admin_kampus'");
if(mysqli_num_rows($check_admin) == 0) {
    $pass_adm = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('admin_kampus', '$pass_adm', 'admin')");
} else {
    // Jika sudah ada, kita paksa update password-nya agar pasti admin123
    $pass_adm = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$pass_adm' WHERE username='admin_kampus'");
}
// --- END OF AUTO REGISTER ---


if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        if(password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            
            if($row['role'] == 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Login - Neo Brutalism</title>
</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card p-4 brutal-card" style="width: 400px; background-color: #a3e635;">
        <h2 class="text-center fw-bold mb-4">MASUK SISTEM</h2>
        <?php if(isset($error)) echo "<p class='text-danger fw-bold'>Username/Password Salah!</p>"; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control brutal-input" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control brutal-input" required>
            </div>
            <button type="submit" name="login" class="btn brutal-btn w-100 mt-2">LOGIN SEKARANG 🔓</button>
        </form>
        
        <p class="text-center fw-bold mt-4 mb-0">
            Belum punya akun? <a href="register.php" class="text-dark text-decoration-underline">Daftar Akun Baru</a>
        </p>
    </div>
</body>
</html>