<?php
session_start();
include __DIR__ . "/../includes/db.php"; // ensure this uses mysqli connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = $_SESSION['otp_mobile'] ?? null;
    $otp = $_POST['otp_code'] ?? null;

    if (!$mobile || !$otp) {
        die("Missing mobile or OTP");
    }

    // Validate OTP (not older than 5 mins)
    $stmt = $conn->prepare("SELECT * FROM otp_requests WHERE mobile = ? AND otp = ? AND created_at >= NOW() - INTERVAL 5 MINUTE ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("ss", $mobile, $otp);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        // Check if user exists
        $stmtUser = $conn->prepare("SELECT id FROM users WHERE mobile = ?");
        $stmtUser->bind_param("s", $mobile);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();
        $user = $resultUser->fetch_assoc();
        $stmtUser->close();

        if (!$user) {
            // Auto-register new user
            $stmtInsert = $conn->prepare("INSERT INTO users (mobile) VALUES (?)");
            $stmtInsert->bind_param("s", $mobile);
            $stmtInsert->execute();
            $userId = $stmtInsert->insert_id;
            $stmtInsert->close();
        } else {
            $userId = $user['id'];
        }

        // Success
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_mobile'] = $mobile;

        echo "OTP Verified. Login Success!";
    } else {
        echo "Invalid or expired OTP!";
    }
}
?>
