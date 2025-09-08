<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login - <?= getenv('COMPANY_NAME'); ?></title>
     <link rel="stylesheet" href="<?= base_url('assets/css/login_admin.css'); ?>">
     <link href="<?= base_url('') ?>assets/img/favicon.png" rel="icon">
     <link href="<?= base_url('') ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">
</head>

<body>
     <div class="login-container">
          <div class="login-form">
               <h2>Login</h2>
               <?php if (session()->getFlashdata('msg')) : ?>
                    <center style="color: red; background-color: pink; padding: 10px;">
                         <div class="alert alert-warning">
                              <?= session()->getFlashdata('msg') ?>
                         </div>
                    </center>
               <?php endif; ?>
               <form action="/loginAuthAdmin" method="POST">
                    <div class="input-group">
                         <label for="username">Username</label>
                         <input type="text" id="username" name="username" value="<?= old('username', session()->getFlashdata('username')); ?>" placeholder="Enter your username" required>
                    </div>
                    <div class="input-group">
                         <label for="password">Password</label>
                         <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn">Login</button>
               </form>
          </div>
     </div>
</body>

</html>