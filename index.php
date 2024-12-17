<?php
session_start(); // Memulai session

// Jika sudah login, arahkan ke dashboard sesuai role
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard-admin.php");
        exit();
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: dashboard-user.php");
        exit();
    }
}

// Proses login jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Simulasi validasi pengguna (seharusnya dari database)
    if ($username === 'admin' && $password === 'admin1234' && $role === 'admin') {
        $_SESSION['username'] = $username; // Simpan username di session
        $_SESSION['role'] = $role; // Simpan role di session
        header("Location: dashboard-admin.php");
        exit();
    } elseif ($username === 'afia' && $password === 'afia1234' && $role === 'user') {
        $_SESSION['username'] = $username; // Simpan username di session
        $_SESSION['role'] = $role; // Simpan role di session
        header("Location: dashboard-user.php");
        exit();
    } else {
        $error_message = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Katalog Buku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Login Container Start -->
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Pilih Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit">Login</button>
            <?php if (isset($error_message)): ?>
                <div id="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>
    </div>
    <!-- Login Container End -->

    <script src="script.js"></script>
</body>
</html>
