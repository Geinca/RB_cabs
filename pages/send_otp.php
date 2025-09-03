<?php
session_start();
include __DIR__ . "/../includes/db.php";

header("Content-Type: application/json"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp_mobile'])) {
    $mobile = trim($_POST['otp_mobile']);

    if (empty($mobile)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mobile number is required"
        ]);
        exit;
    }

    // Generate random 6-digit OTP
    $otp = rand(100000, 999999);

    // Save OTP in DB
    try {
        $stmt = $conn->prepare("INSERT INTO otp_requests (mobile, otp, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$mobile, $otp]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $e->getMessage()
        ]);
        exit;
    }

    // Store in session
    $_SESSION['otp_mobile'] = $mobile;
    $_SESSION['otp'] = $otp;

    // ============================
    // Send OTP using Apihome API
    // ============================
    $apiKey = "4a5aeb4a613756be339f5dce8cd4cd3813687";  // Replace with your real key
    $url = "https://apihome.in/panel/api/bulksms/?key=" . urlencode($apiKey) .
           "&mobile=" . urlencode($mobile) .
           "&otp=" . urlencode($otp);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CUSTOMREQUEST => 'GET'
    ]);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false || !empty($error)) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to send OTP via Apihome",
            "error" => $error
        ]);
        exit;
    }

    echo json_encode([
        "status" => "success",
        "message" => "OTP sent successfully to $mobile",
        "api_response" => $response, // 🔎 keep this to debug, remove later
        "otp" => $otp // ⚠️ For testing only
    ]);
    exit;

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
}
