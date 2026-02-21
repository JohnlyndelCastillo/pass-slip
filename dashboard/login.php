<?php
session_start();
$error = $_SESSION['loginError'] ?? '';
unset($_SESSION['loginError']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/login-style.css?v=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div id="login-container">
    <div class="login-form">
      <h2>Login</h2>
      <h6>Enter your credentials to access your account</h6>

      <?php if ($error): ?>
        <p class="form-error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form action="/auth/login_auth.php" method="POST">

        <!-- Currently the handling of validation errors is server-side and handled by login_auth.php. -->
        <label for="username"></label>
        <input type="text" id="username" name="username" placeholder="Enter username">

        <label for="password"></label>
        <div class="password-container">
          <input type="password" id="password" name="password" placeholder="Enter password">
          <i class="fa-solid fa-eye toggle-password"></i>
        </div>

        <button type="submit">Login</button>

        <h6>Don't have an account? <a href="/dashboard/register.php">Register here</a></h6>
      </form>
    </div>
  </div>
</body>

</html>