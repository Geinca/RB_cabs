<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

  
  <style>
  :root {
            --primary: #ec8b24;
            --primary-dark: #d97c1a;
            --primary-light: #f4a44d;
            --secondary: #1f2937;
            --accent: #059669;
            --light: #f8fafc;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
        }

    .navbar {
      background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
      border-bottom: 3px solid #FFD600;
      padding: 0.8rem 0;
      transition: all 0.3s ease;
    }

    .navbar-brand img {
      transition: transform 0.3s ease;
      border-radius: 8px;
      padding: 5px;
      background: rgba(255, 214, 0, 0.1);
    }

    .navbar-brand img:hover {
      transform: scale(1.05);
    }

    .nav-link {
      color: #f8f9fa !important;
      font-weight: 500;
      padding: 0.5rem 1rem !important;
      margin: 0 0.2rem;
      border-radius: 6px;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-link:hover {
      color: #FFD600 !important;
      background: rgba(255, 214, 0, 0.1);
      transform: translateY(-2px);
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: #FFD600;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .nav-link:hover::after {
      width: 80%;
    }

    .nav-link.active {
      color: #FFD600 !important;
      background: rgba(255, 214, 0, 0.15);
    }

    .dropdown-menu {
      background: linear-gradient(135deg, #2d2d2d 0%, #3d3d3d 100%);
      border: 1px solid #444;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      overflow: hidden;
    }

    .dropdown-item {
      color: #f8f9fa;
      padding: 0.75rem 1.5rem;
      transition: all 0.3s ease;
      border-bottom: 1px solid #444;
    }

    .dropdown-item:last-child {
      border-bottom: none;
    }

    .dropdown-item:hover {
      color: #FFD600;
      background: rgba(255, 214, 0, 0.1);
      transform: translateX(5px);
    }

    .dropdown-item i {
      width: 20px;
      margin-right: 10px;
      color: #FFD600;
    }

    .dropdown-divider {
      border-color: #444;
      margin: 0.5rem 0;
    }

    .navbar-toggler {
      border: 2px solid #FFD600;
      padding: 0.4rem 0.6rem;
    }

    .navbar-toggler:focus {
      box-shadow: 0 0 0 3px rgba(255, 214, 0, 0.25);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 214, 0, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .user-avatar {
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, #FFD600, #FFA000);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 8px;
      font-size: 14px;
      color: #1a1a1a;
      font-weight: bold;
    }
    
    /* Auth Modal Styles */
        .auth-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .auth-modal {
            background: linear-gradient(135deg, #ffffff 0%, #fef7ed 100%);
            border-radius: 20px;
            padding: 2rem;
            width: 90%;
            max-width: 400px;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(236, 139, 36, 0.2);
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.5rem;
            color: var(--gray);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: var(--primary);
        }

        .auth-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 1.5rem;
            background: var(--light);
            border-radius: 12px;
            padding: 5px;
        }

        .auth-tab {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-weight: 600;
            color: var(--gray);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .auth-tab.active {
            background: var(--primary);
            color: white;
        }

        .auth-content {
            display: none;
        }

        .auth-content.active {
            display: block;
        }

        .auth-content h3 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--secondary);
            font-weight: 700;
        }

        .auth-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(236, 139, 36, 0.3);
        }

    @media (max-width: 991.98px) {
      .navbar-collapse {
        background: linear-gradient(135deg, #2d2d2d 0%, #3d3d3d 100%);
        margin-top: 1rem;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      }
      
      .nav-link {
        margin: 0.2rem 0;
        text-align: center;
      }
      
      .dropdown-menu {
        background: rgba(40, 40, 40, 0.95);
        margin: 0.5rem 0;
      }
    }
    
     /* Animation for navbar on scroll */
    .navbar.scrolled {
      padding: 0.5rem 0;
      backdrop-filter: blur(10px);
      background: rgba(26, 26, 26, 0.95) !important;
    }
    
    /* Responsive Design */
        @media (max-width: 768px) {
            .auth-modal {
                padding: 1.5rem;
                margin: 1rem;
            }
        }

   
  </style>

  <nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/cab-booking/index.php">
      <img src="/cab-booking/assets/image/logo.jpg" alt="logo" width="100">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/cab-booking/index.php">
            <i class="fas fa-home me-1"></i>Home
          </a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Logged-in user dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <span class="user-avatar">
                <?php echo strtoupper(substr(htmlspecialchars($_SESSION['user_name']), 0, 1)); ?>
              </span>
              <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="/cab-booking/pages/profile.php">
                  <i class="fas fa-user"></i> Profile
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="/cab-booking/pages/my-bookings.php">
                  <i class="fas fa-calendar-alt"></i> My Bookings
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item" href="/cab-booking/logout.php">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </li>
            </ul>
          </li>
        <?php else: ?>
          <!-- Login Button triggers Modal -->
          <li class="nav-item">
            <a class="nav-link" href="#" id="openLoginModal">
              <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Authentication Modal -->
<div class="auth-overlay" id="authOverlay">
  <div class="auth-modal">
    <span class="close-btn" id="closeAuthModal">&times;</span>

    <div class="auth-tabs">
      <button type="button" class="auth-tab active" data-target="#loginTab">Login</button>
      <button type="button" class="auth-tab" data-target="#signupTab">Sign Up</button>
    </div>

    <!-- Login Form -->
    <div id="loginTab" class="auth-content active">
      <h3>Welcome Back</h3>
      <form method="POST" action="login_process.php">
        <div class="mb-3">
          <input type="email" name="email" placeholder="Enter your Email" class="form-control" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" placeholder="Enter your Password" class="form-control" required>
        </div>
        <button type="submit" class="auth-btn">Login to Continue</button>
      </form>
    </div>

    <!-- Sign Up Form -->
    <div id="signupTab" class="auth-content">
      <h3>Create Account</h3>
      <form method="POST" action="signup_process.php">
        <div class="mb-3">
          <input type="text" name="name" placeholder="Full Name" class="form-control" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" placeholder="Enter your Email" class="form-control" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" placeholder="Create Password" class="form-control" required>
        </div>
        <button type="submit" class="auth-btn">Create Account</button>
      </form>
    </div>
  </div>
</div>



<!-- JS for Modal -->
  <script>
      // Open login modal when clicking "Login" in navbar
  document.getElementById("openLoginModal").addEventListener("click", (e) => {
    e.preventDefault();
    document.getElementById("authOverlay").style.display = "flex";
  });

  // Switch tabs (login/signup)
  document.querySelectorAll(".auth-tab").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".auth-tab").forEach(b => b.classList.remove("active"));
      document.querySelectorAll(".auth-content").forEach(c => c.classList.remove("active"));
      btn.classList.add("active");
      document.querySelector(btn.dataset.target).classList.add("active");
    });
  });

  // Close modal
  document.getElementById("closeAuthModal").addEventListener("click", () => {
    document.getElementById("authOverlay").style.display = "none";
  });

  // Close modal when clicking outside
  document.getElementById("authOverlay").addEventListener("click", (e) => {
    if (e.target.id === "authOverlay") {
      document.getElementById("authOverlay").style.display = "none";
    }
  });
    // Add scroll effect to navbar
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Add active class to current page
    document.addEventListener('DOMContentLoaded', function() {
      const currentLocation = location.href;
      const menuItems = document.querySelectorAll('.nav-link');
      
      menuItems.forEach(item => {
        if (item.href === currentLocation) {
          item.classList.add('active');
        }
      });
    });
  </script>