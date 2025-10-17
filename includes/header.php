<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

  
  <style>
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
          <!--<li class="nav-item">-->
          <!--  <a class="nav-link" href="/cab-booking/pages/booking.php">-->
          <!--    <i class="fas fa-car me-1"></i>Book Now-->
          <!--  </a>-->
          <!--</li>-->

          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Show username + logout -->
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
                    <i class="fas fa-user"></i>Profile
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="/cab-booking/pages/my-bookings.php">
                    <i class="fas fa-calendar-alt"></i>My Bookings
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="/cab-booking/logout.php">
                    <i class="fas fa-sign-out-alt"></i>Logout
                  </a>
                </li>
              </ul>
            </li>
          <?php else: ?>
            <!-- Show login -->
            <li class="nav-item">
              <a class="nav-link" href="/cab-booking/login.php">
                <i class="fas fa-sign-in-alt me-1"></i>Login
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <script>
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
