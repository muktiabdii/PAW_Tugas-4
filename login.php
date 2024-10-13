<?php
    session_start();
    include 'db.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Mengambil dan membersihkan input
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Validasi input
        if (empty($username) || empty($password)) {
            $error = "Semua field wajib diisi.";
        } 
        else {
            // Menggunakan prepared statements
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                if (password_verify($password, $row['password'])) {
                    // Menyimpan data sesi
                    $_SESSION['username'] = $username;
                    header('Location: beasiswa.php');
                    exit;
                } 
                else {
                    $error = "Password tidak valid.";
                }
            } 
            else {
                $error = "Pengguna tidak ditemukan.";
            }
            
            $stmt->close();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <form method="post" action="login.php">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php"><button type="button">Register</button></a></p>
    
        <?php if(isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>

</body>
</html>
