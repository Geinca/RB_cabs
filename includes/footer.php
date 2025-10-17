<footer class="footer-custom pt-5 pb-4">
  <div class="container">
    <div class="row g-4">
      
      <!-- Brand Column -->
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand mb-4">
          <a class="navbar-brand fw-bold" href="/cab-booking/index.php">
            <img src="/cab-booking/assets/image/logo.jpg" alt="RB Cabs Logo" width="120" class="footer-logo">
          </a>
        </div>
        <p class="footer-text mb-4">
          RB Cabs is your trusted partner for **safe, affordable, and comfortable rides** across India. 
          Whether it’s an airport drop, city ride, or an outstation trip — we’ve got you covered 24/7.
        </p>
        <div class="social-icons">
          <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      
      <!-- Quick Links -->
      <div class="col-lg-2 col-md-6">
        <h5 class="footer-heading mb-4">Quick Links</h5>
        <ul class="footer-links">
          <li><a href="/cab-booking/index.php" class="footer-link">Home</a></li>
          <li><a href="/cab-booking/pages/booking.php" class="footer-link">Book a Ride</a></li>
          <li><a href="/cab-booking/pages/fare-calculator.php" class="footer-link">Fare Calculator</a></li>
          <li><a href="/cab-booking/pages/offers.php" class="footer-link">Offers & Discounts</a></li>
          <li><a href="/cab-booking/pages/cities.php" class="footer-link">Available Cities</a></li>
        </ul>
      </div>
      
      <!-- Support Links -->
      <div class="col-lg-3 col-md-6">
        <h5 class="footer-heading mb-4">Customer Support</h5>
        <ul class="footer-links">
          <li><a href="/cab-booking/pages/contact.php" class="footer-link">Contact Us</a></li>
          <li><a href="/cab-booking/pages/faqs.php" class="footer-link">Help & FAQs</a></li>
          <li><a href="/cab-booking/pages/safety.php" class="footer-link">Safety & Hygiene</a></li>
          <li><a href="/cab-booking/pages/privacy.php" class="footer-link">Privacy Policy</a></li>
          <li><a href="/cab-booking/pages/terms.php" class="footer-link">Terms & Conditions</a></li>
        </ul>
      </div>
      
      <!-- Contact Info -->
      <div class="col-lg-3 col-md-6">
        <h5 class="footer-heading mb-4">Get in Touch</h5>
        <div class="contact-info">
          <div class="contact-item mb-3">
            <div class="contact-icon"><i class="fas fa-phone"></i></div>
            <div>
              <h6 class="mb-1">Call Us</h6>
              <a href="tel:+18002001234" class="footer-link">1800 200 1234</a>
            </div>
          </div>
          <div class="contact-item mb-3">
            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
            <div>
              <h6 class="mb-1">Email Support</h6>
              <a href="mailto:support@rbcabs.com" class="footer-link">support@rbcabs.com</a>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div>
              <h6 class="mb-1">Head Office</h6>
              <span class="footer-text">Andheri East, Mumbai, India</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Divider -->
    <div class="footer-divider my-5"></div>
    
    <!-- Copyright -->
    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
        <p class="copyright-text mb-0">© <?php echo date('Y'); ?> 
          <span class="text-warning">RB Cabs.</span> All rights reserved.
        </p>
      </div>
      <div class="col-md-6 text-center text-md-end">
        <p class="designer-text mb-0">
          Made with <i class="fas fa-heart pulse text-danger"></i> by 
          <a href="https://geinca.com/" class="text-warning" style="text-decoration:none;">Geinca</a>
        </p>
      </div>
    </div>
  </div>
  
  <!-- Back to Top Button -->
  <button id="backToTop" class="back-to-top-btn" aria-label="Back to top">
    <i class="fas fa-chevron-up"></i>
  </button>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/cab-booking/assets/js/script.js"></script>

<style>
  /* Footer Base Styles */
  .footer-custom {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: #f8f9fa;
    position: relative;
    overflow: hidden;
  }
  
  .footer-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #FFD600, #FFA000, #FFD600);
    background-size: 200% 100%;
    animation: shimmer 3s infinite linear;
  }
  
  @keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
  }
  
  /* Footer Logo */
  .footer-logo {
    transition: transform 0.3s ease;
    border-radius: 8px;
    padding: 5px;
    background: rgba(255, 214, 0, 0.1);
  }
  
  .footer-logo:hover {
    transform: scale(1.05);
  }
  
  /* Footer Text */
  .footer-text {
    color: #b0b0b0;
    line-height: 1.6;
    font-size: 0.95rem;
  }
  
  /* Footer Headings */
  .footer-heading {
    color: #FFD600;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
  }
  
  .footer-heading::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 2px;
    background: #FFD600;
    border-radius: 2px;
  }
  
  /* Footer Links */
  .footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .footer-links li {
    margin-bottom: 12px;
  }
  
  .footer-link {
    color: #b0b0b0;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
    padding-left: 0;
  }
  
  .footer-link::before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: #FFD600;
    transition: width 0.3s ease;
  }
  
  .footer-link:hover {
    color: #FFD600;
    padding-left: 8px;
  }
  
  .footer-link:hover::before {
    width: 100%;
  }
  
  /* Social Icons */
  .social-icons {
    display: flex;
    gap: 15px;
  }
  
  .social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    color: #b0b0b0;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
  }
  
  .social-link:hover {
    background: #FFD600;
    color: #1a1a1a;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(255, 214, 0, 0.3);
  }
  
  /* Contact Info */
  .contact-info {
    margin-top: 10px;
  }
  
  .contact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }
  
  .contact-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: rgba(255, 214, 0, 0.1);
    border-radius: 50%;
    color: #FFD600;
    flex-shrink: 0;
  }
  
  .contact-item h6 {
    color: #f8f9fa;
    font-size: 0.9rem;
    margin-bottom: 4px;
  }
  
  /* Footer Divider */
  .footer-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 214, 0, 0.3), transparent);
  }
  
  /* Copyright & Designer Text */
  .copyright-text, .designer-text {
    color: #888;
    font-size: 0.9rem;
  }
  
  .pulse {
    animation: pulse 1.5s infinite;
    display: inline-block;
  }
  
  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
  }
  
  /* Back to Top Button */
  .back-to-top-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: #FFD600;
    color: #1a1a1a;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    opacity: 0;
    visibility: hidden;
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(255, 214, 0, 0.3);
  }
  
  .back-to-top-btn.visible {
    opacity: 1;
    visibility: visible;
  }
  
  .back-to-top-btn:hover {
    background: #FFA000;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 214, 0, 0.4);
  }
  
  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .footer-heading::after {
      left: 50%;
      transform: translateX(-50%);
    }
    
    .social-icons {
      justify-content: center;
    }
    
    .back-to-top-btn {
      bottom: 20px;
      right: 20px;
      width: 45px;
      height: 45px;
    }
  }
</style>

<script>
  // Back to top button functionality
  document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTopBtn.classList.add('visible');
      } else {
        backToTopBtn.classList.remove('visible');
      }
    });
    
    backToTopBtn.addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  });
</script>