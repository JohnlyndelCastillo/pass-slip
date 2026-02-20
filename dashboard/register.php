<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/register-style.css?v=1.0">
  <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<script src="../public/js/register-back-button.js"></script>

<body>
  <div id="register-container">
    <div class="register-form">
      <div class="register-header">
        <i class="fa-solid fa-circle-arrow-left back-btn" onclick="redirectToLogin()"></i>
        <h2>Register</h2>
        <div class="header-spacer"></div>
      </div>
      <h6>Enter valid credentials to register your account</h6>
      <form action="dashboard/register.php" method="post">
        <label for="fullname"></label>
        <input type="text" id="fullname" name="fullname" placeholder="Enter Full Name" required>

        <label for="username"></label>
        <input type="text" id="username" name="username" placeholder="Enter Username" required>

        <label for="password"></label>
        <div class="password-container">
          <input type="password" id="password" name="password" placeholder="Enter Password" required>
          <i class="fa-solid fa-eye toggle-password"></i>
        </div>

        <button type="submit">Register</button>

      </form>
    </div>
  </div>
</body>

</html>