<?php
require_once 'phpqrcode/qrlib.php';

$fare = isset($_GET['fare']) ? floatval($_GET['fare']) : 0;
$upi_id = 'mahia1825@ybl'; // your UPI ID
$payee_name = 'My Cab Service';

$upi_link = "upi://pay?pa={$upi_id}&pn=".urlencode($payee_name)."&am={$fare}&cu=INR&tn=".urlencode('Cab Booking');

header('Content-Type: image/png');
QRcode::png($upi_link, false, QR_ECLEVEL_L, 6);
exit;
