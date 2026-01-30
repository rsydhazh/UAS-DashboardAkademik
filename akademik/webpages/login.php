
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Login - Sistem Akademik</title>
    
    <!-- Google icon -->
    <link rel="stylesheet" href="<?php echo $app->config["site"]; ?>/public/fonts/material-icons/material-icons.css">
    
    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="<?php echo $app->config["site"]; ?>/public/css/bootstrap.min.css">
    
    <!-- Propeller css -->
    <link rel="stylesheet" type="text/css" href="<?php echo $app->config["site"]; ?>/public/css/propeller.min.css">
    
    <style>
        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 350px;
            background-color: white;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 15px;
        }
        .login-logo img {
            width: 70px;
        }
        .login-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }
        .login-title strong {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .input-with-icon {
            display: flex;
            align-items: center;
        }
        .icon-container {
            margin-right: 10px;
            color: #757575;
        }
        .form-control {
            height: 36px;
            background-color: #f0f4ff;
            border: none;
            flex-grow: 1;
        }
        .login-btn {
            background-color: #4285f4;
            color: white;
            width: 100%;
            margin-top: 10px;
            height: 40px;
            font-size: 14px;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
        }
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        /* Custom checkbox styling */
        .pmd-checkbox [type="checkbox"]:not(:checked),
        .pmd-checkbox [type="checkbox"]:checked {
            position: absolute;
            left: -9999px;
        }
        .pmd-checkbox [type="checkbox"]:not(:checked) + .pmd-checkbox-label,
        .pmd-checkbox [type="checkbox"]:checked + .pmd-checkbox-label {
            position: relative;
            padding-left: 25px;
            cursor: pointer;
        }
        .pmd-checkbox [type="checkbox"]:not(:checked) + .pmd-checkbox-label:before,
        .pmd-checkbox [type="checkbox"]:checked + .pmd-checkbox-label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 18px;
            height: 18px;
            border: 2px solid #4caf50;
            background: #fff;
            border-radius: 3px;
        }
        .pmd-checkbox [type="checkbox"]:checked + .pmd-checkbox-label:before {
            background: #4caf50;
        }
        .pmd-checkbox [type="checkbox"]:checked + .pmd-checkbox-label:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 11px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .forgot-link {
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }
        .signup-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }
        .signup-link a {
            text-decoration: none;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="<?php echo $app->config["site"]; ?>/public/images/logo-icon.png" alt="Logo">
        </div>
        
        <div class="login-title">
            Sign In <span>with <strong>PROPELLER</strong></span>
        </div>
        
        <form action="<?php echo $app->config["site"]; ?>/index.php?com=Pengguna&task=login" method="post">
            <?php if (isset($_REQUEST["error"])) { ?>
                <?php if ($_REQUEST["error"] == 1) { ?>
                    <div class="alert alert-danger">Username atau password salah!</div>
                <?php } else if ($_REQUEST["error"] == 2) { ?>
                    <div class="alert alert-warning">Username dan password harus diisi!</div>
                <?php } ?>
            <?php } ?>
            
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-with-icon">
                    <div class="icon-container">
                        <i class="material-icons">person_outline</i>
                    </div>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <div class="icon-container">
                        <i class="material-icons">lock_outline</i>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
            </div>
            
            <div class="remember-forgot">
                <div class="pmd-checkbox">
                    <label class="pmd-checkbox-label">
                        <input type="checkbox" value="remember-me">
                        <span class="pmd-checkbox-label">Remember me</span>
                    </label>
                </div>
                <a href="javascript:void(0);" class="forgot-link">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn login-btn">LOGIN</button>
            
            <div class="signup-link">
                Don't have an account? <a href="javascript:void(0);">Sign Up</a>.
            </div>
        </form>
    </div>
    
    <!-- Scripts -->
    <script src="<?php echo $app->config["site"]; ?>/public/js/jquery-1.12.2.min.js"></script>
    <script src="<?php echo $app->config["site"]; ?>/public/js/bootstrap.min.js"></script>
    <script src="<?php echo $app->config["site"]; ?>/public/js/propeller.min.js"></script>
</body>
</html>






