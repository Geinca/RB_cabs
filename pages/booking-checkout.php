<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['booking'])) {
    header("Location: index.php?error=missing_booking");
    exit;
}
$booking = $_SESSION['booking'];
$trip = $_SESSION['trip'] ?? [];

$name = $_SESSION['user_name'];
$pickup = $booking['from'];
$drop = $booking['to'];
$pickup_date = $booking['pickup_date'];
$trip_type = $booking['trip_type'];
$car_id = $booking['car_id'];

include __DIR__."/../includes/db.php";
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$car_type = $car['name'];
$rate = $car['rate_per_km'];
$distance = isset($trip['distance']) ? (float)$trip['distance'] : 0;
if ($trip_type === 'roundtrip') $distance *= 2;
$fare = $distance * $rate;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Booking - RBCabs</title>
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
            --border-radius: 12px;
            --box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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

        .checkout-container {
            max-width: 850px;
            width: 100%;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .checkout-header {
            background: linear-gradient(135deg, var(--secondary) 0%, #333 100%);
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
            border-bottom: 4px solid var(--primary);
        }

        .checkout-header h2 {
            font-size: 2.2rem;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--primary);
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .checkout-header p {
            opacity: 0.9;
            font-size: 1.1rem;
            color: #f0f0f0;
        }

        .header-icon {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 2.5rem;
            color: var(--primary);
            opacity: 0.7;
        }

        .checkout-body {
            padding: 30px;
        }

        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .detail-card {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 20px;
            border-left: 4px solid var(--primary);
            transition: var(--transition);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--secondary);
        }

        .detail-label {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .detail-label i {
            margin-right: 8px;
            font-size: 1rem;
            color: var(--primary);
        }

        .detail-value {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--secondary);
        }

        .fare-section {
            background: linear-gradient(135deg, var(--light) 0%, var(--accent) 100%);
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 214, 0, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .fare-section h3 {
            margin-bottom: 20px;
            color: var(--secondary);
            display: flex;
            align-items: center;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .fare-section h3 i {
            margin-right: 10px;
            color: var(--primary);
        }

        .fare-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
        }

        .fare-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 1.4rem;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--primary);
            color: var(--secondary);
        }

        .payment-section {
            text-align: center;
            padding-top: 10px;
        }

        .btn-pay {
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
            letter-spacing: 0.5px;
        }

        .btn-pay:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 214, 0, 0.6);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #CCAA00 100%);
        }

        .btn-pay:active {
            transform: translateY(0);
        }

        .btn-pay i {
            margin-right: 10px;
            font-size: 1.3rem;
        }

        .secure-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-size: 0.95rem;
        }

        .secure-notice i {
            color: var(--primary);
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .brand-logo img {
            height: 50px;
            margin-right: 10px;
        }

        .brand-logo span {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .trip-highlight {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            padding: 15px;
            background: var(--light);
            border-radius: var(--border-radius);
            border: 1px solid rgba(255, 214, 0, 0.3);
        }

        .trip-highlight i {
            font-size: 1.5rem;
            color: var(--primary);
            margin: 0 15px;
        }

        .location {
            text-align: center;
            flex: 1;
        }

        .location h4 {
            color: var(--secondary);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .location p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .booking-details {
                grid-template-columns: 1fr;
            }
            
            .checkout-body {
                padding: 20px;
            }
            
            .checkout-header {
                padding: 20px;
            }
            
            .header-icon {
                display: none;
            }
            
            .trip-highlight {
                flex-direction: column;
                text-align: center;
            }
            
            .trip-highlight i {
                margin: 10px 0;
                transform: rotate(90deg);
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <div class="brand-logo">
                <span>RBCabs</span>
            </div>
            <i class="fas fa-car header-icon"></i>
            <h2>Confirm Your Booking</h2>
            <p>Review your trip details before proceeding to payment</p>
        </div>
        
        <div class="checkout-body">
            <div class="trip-highlight">
                <div class="location">
                    <h4>Pickup</h4>
                    <p><?= htmlspecialchars($pickup) ?></p>
                </div>
                <i class="fas fa-arrow-right"></i>
                <div class="location">
                    <h4>Drop</h4>
                    <p><?= htmlspecialchars($drop) ?></p>
                </div>
            </div>
            
            <div class="booking-details">
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-user"></i> Passenger Name
                    </div>
                    <div class="detail-value"><?= htmlspecialchars($name) ?></div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-route"></i> Trip Type
                    </div>
                    <div class="detail-value"><?= ucfirst($trip_type) ?></div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-calendar-alt"></i> Pickup Date
                    </div>
                    <div class="detail-value"><?= htmlspecialchars($pickup_date) ?></div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-car"></i> Vehicle Type
                    </div>
                    <div class="detail-value"><?= htmlspecialchars($car_type) ?></div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-road"></i> Distance
                    </div>
                    <div class="detail-value"><?= $distance ?> km</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-tachometer-alt"></i> Rate per km
                    </div>
                    <div class="detail-value">₹<?= number_format($rate, 2) ?></div>
                </div>
            </div>
            
            <div class="fare-section">
                <h3><i class="fas fa-receipt"></i> Fare Breakdown</h3>
                <div class="fare-item">
                    <span>Distance (<?= $distance ?> km)</span>
                    <span>₹<?= number_format($distance * $rate, 2) ?></span>
                </div>
                <div class="fare-item">
                    <span>Rate per km</span>
                    <span>₹<?= number_format($rate, 2) ?></span>
                </div>
                <?php if ($trip_type === 'roundtrip'): ?>
                <div class="fare-item">
                    <span>Round Trip (2x)</span>
                    <span>Applied</span>
                </div>
                <?php endif; ?>
                <div class="fare-total">
                    <span>Total Fare</span>
                    <span>₹<?= number_format($fare, 2) ?></span>
                </div>
            </div>
            
            <div class="payment-section">
                <form action="payment.php" method="post">
                    <input type="hidden" name="fare" value="<?= $fare ?>">
                    <button type="submit" class="btn-pay">
                        <i class="fas fa-lock"></i> Proceed to Payment
                    </button>
                </form>
                <div class="secure-notice">
                    <i class="fas fa-shield-alt"></i> Your payment is secure and encrypted
                </div>
            </div>
        </div>
    </div>
</body>
</html>