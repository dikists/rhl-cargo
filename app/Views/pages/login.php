<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= getenv('COMPANY_NAME'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Favicons -->
    <link href="<?= base_url('') ?>assets/img/favicon.png" rel="icon">
    <link href="<?= base_url('') ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?q=80&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed !important;
            background-size: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .login-form {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            z-index: 2;
            position: relative;
        }

        .login-form h3 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #007bff;
        }

        .login-form .form-control {
            border-radius: 30px;
        }

        .login-form .btn {
            border-radius: 30px;
            background-color: #007bff;
            border: none;
        }

        .login-form .btn:hover {
            background-color: #0056b3;
        }

        .login-form a {
            color: #007bff;
        }

        .login-form a:hover {
            color: #0056b3;
        }

        .login-form .form-check-label {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="login-form">
        <h3>Login</h3>
        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="alert alert-warning">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>
        <form action="/loginAuth" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= session()->getFlashdata('email') ?>" placeholder="Enter email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3 form-check text-start">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div class="mt-3">
                <a href="#">Forgot password?</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>