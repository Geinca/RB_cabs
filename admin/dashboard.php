<?php
include __DIR__ . "/../includes/db.php";
session_start();
if (!isset($_SESSION['admin'])) { header("Location: /cab-booking/admin/login.php"); exit; }

if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  mysqli_query($conn, "DELETE FROM bookings WHERE id=$id");
  header("Location: /cab-booking/admin/dashboard.php");
  exit;
}
?>
<?php include __DIR__ . "/../includes/header.php"; ?>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Bookings</h3>
    <div>
      <a href="/cab-booking/admin/logout.php" class="btn btn-outline-warning btn-sm">Logout</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Pickup</th>
          <th>Drop</th>
          <th>Date</th>
          <th>Car</th>
          <th>Distance</th>
          <th>Total Fare (₹)</th>
          <th>Created</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $res = mysqli_query($conn, "SELECT * FROM bookings ORDER BY created_at DESC");
        while($row = mysqli_fetch_assoc($res)):
      ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['phone']); ?></td>
          <td><?php echo htmlspecialchars($row['pickup']); ?></td>
          <td><?php echo htmlspecialchars($row['drop_location']); ?></td>
          <td><?php echo htmlspecialchars($row['pickup_date']); ?></td>
          <td><?php echo htmlspecialchars($row['car_type']); ?></td>
          <td><?php echo (int)$row['distance']; ?></td>
          <td><?php echo number_format($row['total_fare'], 2); ?></td>
          <td><?php echo $row['created_at']; ?></td>
          <td><a class="btn btn-sm btn-outline-danger" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete booking?')">Delete</a></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>
