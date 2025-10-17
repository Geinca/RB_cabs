<?php
include __DIR__ . "/../includes/db.php";

if (!isset($_GET['booking_id'])) {
    header("Location: index.php");
    exit;
}

$booking_id = (int)$_GET['booking_id'];

// Fetch booking details
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Booking not found.");
}

// Fetch transaction ID
$stmt2 = $conn->prepare("SELECT transaction_id FROM payment_proofs WHERE booking_id = ?");
$stmt2->bind_param("i", $booking_id);
$stmt2->execute();
$payment = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

$fare = $booking['total_fare'] ?? 0;
$transaction_id = $payment['transaction_id'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 120vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .success-wrapper {
            width: 100%;
            max-width: 600px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .success-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }

        .checkmark-circle {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease-out 0.2s both;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .checkmark-circle i {
            font-size: 50px;
            color: #10b981;
            animation: checkPop 0.3s ease-out 0.5s both;
        }

        @keyframes checkPop {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        .success-header h1 {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .success-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin: 0;
        }

        .success-body {
            padding: 40px 30px;
        }

        .amount-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            border: 2px solid #fbbf24;
        }

        .amount-label {
            font-size: 14px;
            color: #92400e;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .amount-value {
            font-size: 48px;
            font-weight: 800;
            color: #b45309;
            line-height: 1;
        }

        .details-section {
            background: #f9fafb;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-row:first-child {
            padding-top: 0;
        }

        .detail-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            font-size: 15px;
            color: #111827;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .buttons-section {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-custom {
            flex: 1;
            min-width: 200px;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-secondary-custom {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary-custom:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .success-footer {
            text-align: center;
            padding: 20px 30px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .success-footer p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .success-footer i {
            color: #ef4444;
            margin: 0 4px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .success-header {
                padding: 40px 25px;
            }

            .success-header h1 {
                font-size: 26px;
            }

            .success-header p {
                font-size: 14px;
            }

            .checkmark-circle {
                width: 80px;
                height: 80px;
            }

            .checkmark-circle i {
                font-size: 40px;
            }

            .success-body {
                padding: 30px 20px;
            }

            .amount-section {
                padding: 25px 20px;
            }

            .amount-value {
                font-size: 40px;
            }

            .details-section {
                padding: 20px;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .detail-value {
                width: 100%;
                text-align: center;
            }

            .buttons-section {
                flex-direction: column;
                gap: 12px;
            }

            .btn-custom {
                min-width: 100%;
                width: 100%;
            }

            .success-footer {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .success-header h1 {
                font-size: 22px;
            }

            .amount-value {
                font-size: 36px;
            }

            .amount-section {
                padding: 20px 15px;
            }

            .details-section {
                padding: 18px;
            }

            .btn-custom {
                padding: 14px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="success-wrapper">
        <div class="success-card">
            <!-- Header Section -->
            <div class="success-header">
                <div class="checkmark-circle">
                    <i class="fas fa-check"></i>
                </div>
                <h1>Booking Confirmed!</h1>
                <p>Your ride has been successfully booked</p>
            </div>

            <!-- Body Section -->
            <div class="success-body">
                <!-- Amount Section -->
                <div class="amount-section">
                    <div class="amount-label">Total Amount Paid</div>
                    <div class="amount-value">₹<?php echo number_format((float)$fare, 2); ?></div>
                </div>

                <!-- Details Section -->
                <div class="details-section">
                    <div class="detail-row">
                        <span class="detail-label">Booking Reference</span>
                        <span class="detail-value">#<?php echo htmlspecialchars($booking_id); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Transaction ID</span>
                        <span class="detail-value"><?php echo htmlspecialchars($transaction_id); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value" style="color: #059669; background: #d1fae5;">CONFIRMED</span>
                    </div>
                </div>

                <!-- Buttons Section -->
                <div class="buttons-section">
                    <a href="/cab-booking/user/my-bookings.php" class="btn-custom btn-primary-custom">
                        <i class="fas fa-list"></i>
                        <span>View My Bookings</span>
                    </a>
                    <a href="/cab-booking/index.php" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-home"></i>
                        <span>Back to Home</span>
                    </a>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="success-footer">
                <p>Thank you for choosing our service <i class="fas fa-heart"></i></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>