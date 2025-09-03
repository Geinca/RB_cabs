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

<?php include __DIR__ . "/../includes/header.php"; ?>
<div class="container py-5" style="max-width:520px;">
  <h3 class="mb-3">Admin Login</h3>
  <?php if($error): ?>
    <div class="alert alert-danger py-2"><?php echo $error; ?></div>
  <?php endif; ?>
  <form method="post" class="border p-4 bg-light rounded">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-dark w-100">Login</button>
  </form>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>
