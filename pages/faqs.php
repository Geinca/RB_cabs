<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Booking | Premium Cab Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include('../includes/header.php'); ?> 

<section class="page-section py-5">
  <div class="container">
    <h1 class="mb-4 text-warning">Help & FAQs</h1>
    
    <div class="accordion" id="faqAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header" id="faq1">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
            How do I book a cab with RB Cabs?
          </button>
        </h2>
        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Simply click "Book a Ride", enter pickup and drop location, choose your cab type, and confirm.
          </div>
        </div>
      </div>
      
      <div class="accordion-item">
        <h2 class="accordion-header" id="faq2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
            Can I schedule a ride in advance?
          </button>
        </h2>
        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, you can schedule rides up to 7 days in advance using RB Cabs.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('../includes/footer.php'); ?>
</body>
</html>
