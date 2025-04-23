<?php
require 'function.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $cekdatabase = mysqli_query($conn, "SELECT * FROM login WHERE email='$email' and password='$password'");
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $_SESSION['log'] = 'True';
        header('location:index.php');
    } else {
        $error = "Email atau Password salah!";
    };
}

if (isset($_SESSION['log'])) {
    header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login | Muria Global Network</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(80deg, #007bff, #6f42c1);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .login-card {
            padding: 40px 30px;
        }

        .login-header {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-floating label {
            color:rgb(10, 10, 10);
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            font-weight: 500;
        }


        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
        }

        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
        }

        .btn-primary {
            font-weight: 500;
            padding: 10px;
            border-radius: 12px;
            background-color: #6f42c1;
            border-color: #6f42c1;
        }

        .btn-primary:hover {
            background-color: #5a379e;
            border-color: #5a379e;
        }

        .footer-note {
            font-size: 0.85rem;
            color: #e0e0e0;
        }

        .card-footer {
            background: transparent;
        }

        .logo-img {
            width: 70px;
            display: block;
            margin: 0 auto 10px;
        }

        a {
            color: #f0f0f0;
        }
    </style>

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card mt-5">
                    <div class="card-header bg-transparent text-center">
                        <img src="images/logo.png" alt="Logo" class="logo-img">
                        <div class="login-header">MURIA GLOBAL NETWORK</div>
                    </div>
                    <div class="login-card">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger text-center"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="form-floating mb-3">
                                <input class="form-control" name="email" id="inputEmail" type="email" placeholder="name@example.com" required autofocus />
                                <label for="inputEmail">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required />
                                <label for="inputPassword">Password</label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a class="small" href="password.html">Lupa Password?</a>
                            </div>
                            <button class="btn btn-primary w-100" name="login">Login</button>
                        </form>
                        <div class="text-center mt-3 footer-note">
                            © 2025 PT Muria Global Network
                        </div>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="register.html">Sign Up</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
