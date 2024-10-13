<?php
    session_start();
    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Mengambil dan membersihkan input
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi input
        if (empty($username) || empty($password) || empty($confirm_password)) {
            $error = "Semua field wajib diisi.";
        } 
        
        elseif ($password !== $confirm_password) {
            $error = "Password dan Konfirmasi Password tidak cocok.";
        } 
        
        else {
            // Cek apakah username sudah ada
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Username sudah digunakan.";
            } 
            
            else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert pengguna baru
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hashed_password);

                if ($stmt->execute()) {
                    $_SESSION['username'] = $username; 
                    header('Location: beasiswa.php');
                    exit;
                } 
                
                else {
                    $error = "Terjadi kesalahan saat registrasi: " . $conn->error;
                }
            }

            $stmt->close();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <?php if(isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="post" action="register.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php"><button type="button">Login</button></a></p>
    </div>
</body>
</html>
