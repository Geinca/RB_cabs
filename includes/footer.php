<footer class="bg-dark text-white pt-5 pb-4">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="d-flex align-items-start mb-3">
          <a class="navbar-brand fw-bold" href="/cab-booking/index.php">
            <span class="text-warning"><i class="fas fa-car-side me-2"></i>Cab</span>Booking
          </a>
        </div>
        <p class="text-white">Your reliable partner for safe and comfortable rides across cities in India.</p>
        <div class="social-icons mt-4">
          <a href="#" class="text-warning me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
          <a href="#" class="text-warning me-3"><i class="fab fa-twitter fa-lg"></i></a>
          <a href="#" class="text-warning me-3"><i class="fab fa-instagram fa-lg"></i></a>
          <a href="#" class="text-warning"><i class="fab fa-linkedin-in fa-lg"></i></a>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6">
        <h5 class="text-warning mb-4">Quick Links</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="/cab-booking/index.php" class="text-white text-decoration-none hover-warning">Home</a></li>
          <li class="mb-2"><a href="/cab-booking/pages/booking.php" class="text-white text-decoration-none hover-warning">Book a Cab</a></li>
          <li class="mb-2"><a href="#" class="text-white text-decoration-none hover-warning">Fare Calculator</a></li>
          <li class="mb-2"><a href="#" class="text-white text-decoration-none hover-warning">Offers</a></li>
        </ul>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <h5 class="text-warning mb-4">Support</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="text-white text-decoration-none hover-warning">Contact Us</a></li>
          <li class="mb-2"><a href="#" class="text-white text-decoration-none hover-warning">FAQs</a></li>
          <li class="mb-2"><a href="#" class="text-white text-decoration-none hover-warning">Privacy Policy</a></li>
          <li class="mb-2"><a href="#" class="text-white text-decoration-none hover-warning">Terms & Conditions</a></li>
        </ul>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="mt-4">
          <h6 class="text-warning">Call Us</h6>
          <a href="tel:+18002001234" class="text-white text-decoration-none hover-warning">1800 200 1234</a>
        </div>
      </div>
    </div>
    
    <hr class="my-4 border-warning opacity-25">
    
    <div class="row">
      <div class="col-md-6 text-center text-md-start">
        <p class="small mb-0">© <?php echo date('Y'); ?> CabBooking. All rights reserved.</p>
      </div>
      <div class="col-md-6 text-center text-md-end">
        <p class="small mb-0">Designed with <i class="fas fa-heart text-danger"></i> by Geinca</p>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/cab-booking/assets/js/script.js"></script>

<style>
  .hover-warning {
    transition: all 0.3s ease;
  }
  
  .hover-warning:hover {
    color: var(--bs-warning) !important;
    padding-left: 5px;
  }
  
  .social-icons a {
    transition: all 0.3s ease;
    display: inline-block;
  }
  
  .social-icons a:hover {
    color: var(--bs-warning) !important;
    transform: translateY(-3px);
  }
  
  footer {
    background: linear-gradient(135deg, #121212 0%, #000000 100%);
  }
  
  footer a {
    text-decoration: none;
  }
</style>