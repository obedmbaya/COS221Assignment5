<?php
$pageTitle="Login";
$currentPage="login";
include 'header.php';
?>
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>CompareIt - Login</title>
<link rel="stylesheet" href="css/login.css">
<script src="js/login.js"></script>
</head>


<body>

            <main class="main-content">
                <div class="login-container">
                    <div class="logo">
                        <h2>Welcome Back</h2>
                                <p>Log in to access your CompareIt account</p>
                            </div>
                            
                                <!-- linking php for login -->

                            <form action= "process_login.php" method="POST" id="login-form">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Enter your email" required>


                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                                </div>
                                <div class="form-options">
                                    <div class="remember-me">
                                        <input type="checkbox" id="remember" name="remember" >
                                        <label for="remember">Remember me</label>

                                    </div>
                                    
                                </div>
                                <button type="submit" class="submit-btn">Login</button>

                              
                            </form>

                            <div class="signup-link">
                            <!-- linking signup page -->

                                <p class="signup-link">Don't have an account? <a href="signup.php"  class="text-brand font-medium">Sign up</a></p>
                            </div>
                        </div>
                   
            </main>

    <?php
        include 'footer.php';

        // echo '<script src="js/login.js"></script>';
    ?>
  
 </body>
 
