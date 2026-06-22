<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password - ASCLEPIUS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family: 'Poppins', sans-serif;
    }

    body{
      background: #f4f6fb;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .container{
      width: 100vw;
      max-width: none;
      min-height: 100vh;
      background: #fff;
      position: relative;
      overflow: hidden;
      display: flex;
      border-radius: 0;
      box-shadow: none;
    }

    .left-section{
      width: 50%;
      padding: 50px 60px;
      position: relative;
      z-index: 2;
    }

    .logo{
      width: 110px;
      margin-bottom: 20px;
    }

    .branding{
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 18px;
    }

    .company-text{
      display: flex;
      flex-direction: column;
      line-height: 1;
    }

    .company-main{
      color: #0aa6a6;
      font-size: 34px;
      font-weight: 800;
      letter-spacing: 2px;
    }

    .company-sub{
      color: #0aa6a6;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      margin-top: 6px;
    }

    .system-title{
      display: inline-block;
      border: 3px solid #1d2433;
      border-radius: 40px;
      padding: 10px 35px;
      font-size: 42px;
      font-weight: 600;
      color: #1d2433;
      margin-bottom: 50px;
    }

    .form-container{
      width: 320px;
      margin-left: 40px;
    }

    .step-title{
      font-size: 26px;
      font-weight: 700;
      color: #1d2433;
      margin-bottom: 28px;
    }

    .hidden{
      display: none;
    }

    .input-group{
      margin-bottom: 12px;
    }

    .input-group label{
      display: block;
      font-size: 12px;
      color: #555;
      margin-bottom: 6px;
    }

    .input-group input{
      width: 100%;
      padding: 10px 12px;
      border: 2px solid #c9ced8;
      border-radius: 6px;
      outline: none;
      font-size: 14px;
      transition: border-color 0.3s ease;
    }

    .input-group input:hover{
      border-color: #2bb18f;
    }

    .submit-btn{
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 5px;
      background: #2bb18f;
      color: #fff;
      font-size: 22px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .submit-btn:hover{
      background: #259676;
      transform: scale(1.02) translateY(-2px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .submit-btn:active{
      transform: scale(0.98);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .back-link{
      margin-top: 18px;
      text-align: center;
      font-size: 14px;
      color: #5f6f8d;
    }

    .back-link a{
      color: #2bb18f;
      text-decoration: none;
      font-weight: 600;
      margin-left: 6px;
      transition: all 0.3s ease;
    }

    .back-link a:hover{
      color: #259676;
      text-decoration: underline;
      transform: translateY(-1px);
    }

    .right-section{
      width: 50%;
      position: relative;
      overflow: hidden;
    }

    .right-section img{
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .curve{
      position: absolute;
      top: -120px;
      right: 35%;
      width: 700px;
      height: 130%;
      background: white;
      border-radius: 50%;
      z-index: 1;
    }

    .blue-shape-top{
      position: absolute;
      top: -80px;
      left: 38%;
      width: 220px;
      height: 250px;
      background: rgba(70, 95, 170, 0.7);
      border-radius: 50%;
      transform: rotate(20deg);
      z-index: 0;
    }

    .blue-shape-bottom{
      position: absolute;
      bottom: -120px;
      right: -70px;
      width: 350px;
      height: 280px;
      background: rgba(70, 95, 170, 0.7);
      border-radius: 50%;
      z-index: 0;
    }

    @media(max-width: 1024px){
      .container{
        flex-direction: column;
        height: auto;
      }

      .left-section,
      .right-section{
        width: 100%;
      }

      .form-container{
        width: 100%;
        margin-left: 0;
      }

      .system-title{
        font-size: 28px;
      }

      .curve{
        display: none;
      }
    }
  </style>
</head>
<body>

  <div class="container">

    <div class="curve"></div>
    <div class="blue-shape-top"></div>
    <div class="blue-shape-bottom"></div>

    <div class="left-section">

      <div class="branding">
        <img src="ASCLEPIUS.jpg" class="logo" alt="Logo">
        <div class="company-text">
          <div class="company-main">ASCLEPIUS</div>
          <div class="company-sub">Medical & Diagnostic Group Inc.</div>
        </div>
      </div>

      <div class="form-container">

        <div id="step-email">
          <div class="step-title">Forgot Password</div>
          <div class="input-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" placeholder="Enter your email">
          </div>
          <button class="submit-btn" onclick="goToPasswordStep()">NEXT</button>
        </div>

        <div id="step-password" class="hidden">
          <div class="step-title">Reset Your Password</div>
          <div class="input-group">
            <label for="password">New Password</label>
            <input id="password" type="password" placeholder="Enter new password">
          </div>
          <div class="input-group">
            <label for="confirmPassword">Confirm Password</label>
            <input id="confirmPassword" type="password" placeholder="Confirm new password">
          </div>
          <button class="submit-btn" onclick="saveNewPassword()">SAVE PASSWORD</button>
          <div class="back-link" style="margin-top: 12px;">
            <a href="#" onclick="backToEmailStep(); return false;">Back to Email</a>
          </div>
        </div>

        <div class="back-link">
          <span>Remember your password?</span>
          <a href="index.php">Login</a>
        </div>

      </div>
    </div>

    <div class="right-section">
      <img src="Doctors.webp" alt="Doctor">
    </div>

  </div>

  <script>
    function goToPasswordStep() {
      const email = document.getElementById('email').value.trim();
      if (!email) {
        alert('Please enter your email address.');
        return;
      }
      document.getElementById('step-email').classList.add('hidden');
      document.getElementById('step-password').classList.remove('hidden');
    }

    function backToEmailStep() {
      document.getElementById('step-password').classList.add('hidden');
      document.getElementById('step-email').classList.remove('hidden');
    }

    function saveNewPassword() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      if (!password || !confirmPassword) {
        alert('Please fill in both password fields.');
        return;
      }
      if (password !== confirmPassword) {
        alert('Passwords do not match. Please try again.');
        return;
      }
      alert('Your password has been reset successfully!');
      window.location.href = 'index.php';
    }
  </script>

</body>
</html>

