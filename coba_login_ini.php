<?php
// Ultimate Debug Script for Tesso Nilo Login
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

echo "<h1>Tesso Nilo Login Debugger</h1>";

// 1. Check Files
echo "<h3>1. File Check</h3>";
$files = ['config/database.php', 'api/auth/login.php', 'api/init_db.php'];
foreach ($files as $file) {
    echo "$file: " . (file_exists($file) ? "<b style='color:green'>EXISTS</b>" : "<b style='color:red'>MISSING</b>") . "<br>";
}

// 2. Try DB Connection
echo "<h3>2. Database Connection</h3>";
try {
    require_once 'config/database.php';
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }
    echo "Connection to <b>" . DB_NAME . "</b>: <b style='color:green'>SUCCESS</b><br>";
} catch (Exception $e) {
    echo "Connection: <b style='color:red'>FAILED</b> (Error: " . $e->getMessage() . ")<br>";
}

// 3. Check Session
echo "<h3>3. Session System</h3>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "Session: <b style='color:green'>ACTIVE</b><br>";
    echo "Session ID: " . session_id() . "<br>";
    echo "Session Save Path: " . session_save_path() . "<br>";
} else {
    echo "Session: <b style='color:red'>INACTIVE</b><br>";
}

// 4. Verify Admin Credentials
echo "<h3>4. Admin Credentials Check</h3>";
$user_test = 'admin';
$pass_test = '12345';

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $user_test);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        echo "User 'admin' in DB: <b style='color:green'>FOUND</b><br>";
        echo "Stored Hash: <code>" . $user['password'] . "</code><br>";
        if (password_verify($pass_test, $user['password'])) {
            echo "Password Verify ('12345'): <b style='color:green'>MATCH</b><br>";
        } else {
            echo "Password Verify ('12345'): <b style='color:red'>MISMATCH</b><br>";
            echo "<b>Resetting password to '12345' now...</b><br>";
            $newHash = password_hash($pass_test, PASSWORD_BCRYPT);
            $conn->query("UPDATE users SET password = '$newHash' WHERE username = 'admin'");
            echo "Password has been reset. Please try login again.<br>";
        }
    } else {
        echo "User 'admin' in DB: <b style='color:red'>NOT FOUND</b><br>";
        echo "<b>Repairing: Creating admin user...</b><br>";
        $newHash = password_hash($pass_test, PASSWORD_BCRYPT);
        $conn->query("INSERT INTO users (username, password, role, full_name, email) VALUES ('admin', '$newHash', 'admin', 'Administrator', 'admin@tessonilo.com')");
    }
} else {
    echo "Query Prepare: <b style='color:red'>FAILED</b> (" . $conn->error . ")<br>";
}

echo "<h3>5. API Test</h3>";
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$uri = "/tessonilov3/api/auth/login.php";
$url = "$protocol://$host$uri";
echo "API URL detected as: <code>$url</code><br>";
echo "<p>Siakan buka URL di atas di browser. Jika muncul pesan 'Invalid request method', berarti API sudah aktif dan bisa diakses.</p>";

echo "<hr><p><b>Saran:</b> Jika semua tes di atas HIJAU tapi masih gagal, coba di browser lain atau hapus Cache (Ctrl+Shift+R).</p>";

ob_end_flush();
?>
