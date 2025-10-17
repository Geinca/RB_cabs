<?php
include __DIR__ . "/../includes/db.php";
session_start();

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = $_POST['password'];

  $res = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' LIMIT 1");
  if ($res && mysqli_num_rows($res) === 1) {
    $row = mysqli_fetch_assoc($res);
    if (password_verify($password, $row['password'])) {
        $_SESSION['admin_id'] = $row['id'];   // use admin_id for dashboard checks
        $_SESSION['admin'] = $row['username'];
        header("Location: /cab-booking/admin/dashboard.php");
        exit;
    } else {
      $error = "Invalid credentials.";
    }
  } else {
    $error = "Invalid credentials.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RB Cabs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #242424;
            --accent: #FFBC00;
            --white: #fff;
            --gray-light: #f8f9fa;
            --gray-dark: #6c757d;
            --transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 25px rgba(255, 188, 0, 0.3);
        }

        .brand-logo i {
            font-size: 2.5rem;
            color: var(--primary);
        }

        .login-header h1 {
            color: var(--white);
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: var(--gray-dark);
            font-size: 1.1rem;
            margin: 0;
        }

        .login-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: none;
        }

        .login-card-body {
            padding: 2.5rem;
        }

        .form-label {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(255, 188, 0, 0.15);
            background: var(--white);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-dark);
            z-index: 5;
        }

        .btn-login {
            background: var(--accent);
            color: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: var(--transition);
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: #ffc929;
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 188, 0, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            margin-right: 8px;
        }

        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            border-left: 4px solid #198754;
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .login-footer p {
            color: var(--gray-dark);
            margin: 0;
            font-size: 0.9rem;
        }

        .password-toggle {
            background: none;
            border: none;
            color: var(--gray-dark);
            cursor: pointer;
            padding: 0;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Loading animation for button */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            border-right-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-card-body {
                padding: 2rem 1.5rem;
            }
            
            .login-header h1 {
                font-size: 1.8rem;
            }
            
            .brand-logo {
                width: 70px;
                height: 70px;
            }
            
            .brand-logo i {
                font-size: 2rem;
            }
        }

        /* Custom focus states */
        .form-control:focus + .input-icon {
            color: var(--accent);
        }

        /* Enhanced hover effects */
        .form-control:hover {
            border-color: #ced4da;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="brand-logo">
                <i class="fas fa-lock"></i>
            </div>
            <h1>Admin Portal</h1>
            <p>RB Cabs</p>
        </div>

        <div class="login-card">
            <div class="login-card-body">
                <?php if($error): ?>
                    <div class="alert alert-danger alert-custom">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['success']) && $_GET['success'] == '1'): ?>
                    <div class="alert alert-success alert-custom">
                        <i class="fas fa-check-circle me-2"></i>
                        Logged out successfully
                    </div>
                <?php endif; ?>

                <form method="post" id="loginForm">
                    <div class="mb-4">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login" id="loginButton">
                        <i class="fas fa-sign-in-alt"></i>
                        Login to Dashboard
                    </button>
                </form>

                <div class="login-footer">
                    <p><i class="fas fa-shield-alt me-2"></i>Secure Admin Access</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            // Form submission with loading state
            loginForm.addEventListener('submit', function() {
                loginButton.classList.add('btn-loading');
                loginButton.disabled = true;
                
                // Simulate loading for demo (remove in production)
                setTimeout(() => {
                    loginButton.classList.remove('btn-loading');
                    loginButton.disabled = false;
                }, 2000);
            });

            // Add focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
</body>
</html>