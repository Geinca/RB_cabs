<section class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold mb-3">Our Services</h2>
      <p class="lead text-muted">Choose the perfect ride for your journey</p>
    </div>
    
    <div class="row g-4 justify-content-center" id="servicesContainer">
      <!-- JS will inject cards here -->
    </div>
  </div>
</section>

<script>
  // Service Data
  const services = [
    {
      icon: "fas fa-arrow-right",
      title: "One Way Trip",
      text: "Affordable and convenient single drop service to your destination with professional drivers.",
      link: "#"
    },
    {
      icon: "fas fa-exchange-alt",
      title: "Round Trip",
      text: "Comfortable travel with return journey included and waiting time flexibility.",
      link: "#"
    },
    {
      icon: "fas fa-hourglass-half",
      title: "Hourly Rental",
      text: "Flexible hourly bookings with driver for meetings, events or shopping.",
      link: "#"
    }
  ];

  const container = document.getElementById("servicesContainer");

  // Loop and render
  services.forEach(service => {
    container.innerHTML += `
      <div class="col-lg-4 col-md-6">
        <div class="card feature-card h-100 border-0 shadow-sm hover-effect">
          <div class="card-body text-center p-4 p-lg-5">
            <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle mx-auto mb-4">
              <i class="${service.icon} text-warning fs-1"></i>
            </div>
            <h5 class="card-title fw-bold mb-3">${service.title}</h5>
            <p class="card-text text-muted">${service.text}</p>
            <a href="${service.link}" class="btn btn-link text-warning fw-bold text-decoration-none mt-2">
              Book Now <i class="fas fa-chevron-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    `;
  });
</script>