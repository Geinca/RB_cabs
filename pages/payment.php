<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['booking'])) {
    header("Location: ../index.php?error=missing_booking");
    exit;
}
$fare = $_POST['fare'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan & Pay - RBCabs</title>
    <style>
        :root {
            --primary: #FFD600;
            --primary-dark: #E6C100;
            --secondary: #000000;
            --accent: #FFE766;
            --light: #ffffff;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #f8f9fa;
            --border-radius: 16px;
            --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .payment-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .payment-header {
            background: linear-gradient(135deg, var(--secondary) 0%, #333 100%);
            color: white;
            padding: 25px 30px;
            position: relative;
            border-bottom: 4px solid var(--primary);
        }

        .payment-header h2 {
            font-size: 2rem;
            margin-bottom: 8px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .payment-header h2 i {
            margin-right: 12px;
            font-size: 1.8rem;
        }

        .payment-header p {
            opacity: 0.9;
            font-size: 1.1rem;
            color: #f0f0f0;
        }

        .brand-logo {
            position: absolute;
            top: 20px;
            left: 30px;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .payment-body {
            padding: 30px;
        }

        .amount-display {
            background: linear-gradient(135deg, var(--light) 0%, var(--accent) 100%);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 30px;
            border: 2px dashed rgba(255, 214, 0, 0.5);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .amount-label {
            font-size: 1rem;
            color: var(--gray);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .amount-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .amount-value::before {
            content: "₹";
            font-size: 2rem;
            margin-right: 5px;
            color: var(--secondary);
        }

        .qr-container {
            position: relative;
            margin: 0 auto 30px;
            width: 220px;
            height: 220px;
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        .qr-code {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 214, 0, 0.3);
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .scanning-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            animation: scan 2s infinite;
            border-radius: 2px;
            z-index: 10;
        }

        @keyframes scan {
            0% { transform: translateY(0); }
            50% { transform: translateY(216px); }
            100% { transform: translateY(0); }
        }

        .payment-instructions {
            background: linear-gradient(135deg, var(--light) 0%, var(--accent) 100%);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
            border: 1px solid rgba(255, 214, 0, 0.3);
        }

        .payment-instructions h3 {
            margin-bottom: 15px;
            color: var(--secondary);
            display: flex;
            align-items: center;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .payment-instructions h3 i {
            margin-right: 10px;
            color: var(--primary);
        }

        .instructions-list {
            list-style-type: none;
        }

        .instructions-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }

        .instructions-list li i {
            color: var(--primary);
            margin-right: 10px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .payment-form {
            text-align: center;
        }

        .upload-proof {
            margin-bottom: 20px;
            text-align: left;
            background: var(--light);
            padding: 15px;
            border-radius: var(--border-radius);
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .upload-proof label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--secondary);
        }

        .upload-proof input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px dashed rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .upload-proof input[type="file"]:hover {
            border-color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
            margin-bottom: 15px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 214, 0, 0.2);
        }

        .btn-confirm {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--secondary);
            border: none;
            border-radius: 50px;
            padding: 16px 45px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(255, 214, 0, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            width: 100%;
            max-width: 300px;
            letter-spacing: 0.5px;
        }

        .btn-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 214, 0, 0.6);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #CCAA00 100%);
        }

        .btn-confirm:active {
            transform: translateY(0);
        }

        .btn-confirm i {
            margin-right: 10px;
            font-size: 1.3rem;
        }

        .secure-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-size: 0.95rem;
            margin-top: 20px;
        }

        .secure-notice i {
            color: var(--primary);
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .upi-apps {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .upi-app {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            font-size: 1.5rem;
            color: var(--secondary);
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .upi-app:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: var(--primary);
        }

        .preview-container {
            background: var(--light);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            display: none;
        }

        .preview-container p {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 10px;
            text-align: left;
        }

        #screenshotPreview {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 5px;
            border-radius: 6px;
            background: white;
        }

        @media (max-width: 768px) {
            .payment-body {
                padding: 20px;
            }
            
            .payment-header {
                padding: 20px;
            }
            
            .amount-value {
                font-size: 2rem;
            }
            
            .qr-container {
                width: 200px;
                height: 200px;
            }
            
            .brand-logo {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
   <div class="payment-container">
    <div class="payment-header">
        <div class="brand-logo">RBCabs</div>
        <h2><i class="fas fa-qrcode"></i> Scan & Pay</h2>
        <p>Complete your payment using UPI</p>
    </div>

    <div class="payment-body">
        <div class="amount-display">
            <div class="amount-label">Amount to Pay</div>
            <div class="amount-value"><?= number_format($fare, 2) ?></div>
        </div>

        <div class="qr-container">
            <div class="scanning-animation"></div>
            <div class="qr-code">
                <img src="generate-qr.php?fare=<?=$fare?>" alt="UPI QR Code">
            </div>
        </div>

        <div class="payment-instructions">
            <h3><i class="fas fa-info-circle"></i> How to pay</h3>
            <ul class="instructions-list">
                <li><i class="fas fa-check-circle"></i> Open any UPI app on your phone</li>
                <li><i class="fas fa-check-circle"></i> Tap on 'Scan QR Code'</li>
                <li><i class="fas fa-check-circle"></i> Point your camera at the QR code above</li>
                <li><i class="fas fa-check-circle"></i> Enter the amount and confirm payment</li>
            </ul>
        </div>

        <div class="payment-form">
            <form action="confirm-payment.php" method="post" enctype="multipart/form-data" id="paymentForm">
                <!-- Upload Screenshot -->
                <div class="upload-proof mb-3">
                    <label for="payment_screenshot">Upload Payment Screenshot:</label>
                    <input type="file" name="payment_screenshot" id="payment_screenshot" accept="image/*" required>
                </div>

                <!-- Live preview -->
                <div class="preview-container mb-3">
                    <p>Preview Screenshot:</p>
                    <img id="screenshotPreview" src="" alt="Payment Screenshot Preview">
                </div>

                <!-- Transaction ID -->
                <div class="mb-3">
                    <input type="text" name="transaction_id" id="transaction_id" class="form-control"
                           placeholder="Enter Transaction ID" required>
                </div>

                <button type="submit" class="btn-confirm">
                    <i class="fa-solid fa-wallet"></i> Pay Now
                </button>
            </form>

            <div class="secure-notice">
                <i class="fas fa-shield-alt"></i> Your payment is secure and encrypted
            </div>
        </div>

        <div class="upi-apps">
            <div class="upi-app"><i class="fab fa-google-pay"></i></div>
            <div class="upi-app"><i class="fas fa-mobile-alt"></i></div>
            <div class="upi-app"><i class="fab fa-amazon-pay"></i></div>
            <div class="upi-app"><i class="fas fa-university"></i></div>
            <div class="upi-app"><i class="fab fa-paypal"></i></div>
        </div>
    </div>
</div>
    
    <script>
const fileInput = document.getElementById("payment_screenshot");
const previewContainer = document.querySelector(".preview-container");
const screenshotPreview = document.getElementById("screenshotPreview");

fileInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
        if (!allowedTypes.includes(file.type)) {
            alert("Only JPG and PNG images are allowed.");
            fileInput.value = "";
            previewContainer.style.display = "none";
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert("Screenshot size must be less than 5MB.");
            fileInput.value = "";
            previewContainer.style.display = "none";
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            screenshotPreview.src = e.target.result;
            previewContainer.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = "none";
    }
});

// Validate form before submitting
document.getElementById("paymentForm").addEventListener("submit", function (e) {
    const transactionId = document.getElementById("transaction_id").value.trim();
    if (!fileInput.files.length) {
        alert("Please upload a payment screenshot.");
        e.preventDefault();
        return;
    }
    if (transactionId.length < 5) {
        alert("Please enter a valid Transaction ID.");
        e.preventDefault();
        return;
    }
});
</script>
</body>
</html>