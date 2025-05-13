<?php
$pageTitle="Login";
$currentPage="login";
include 'header.php';
?>
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>CompareIt - Login</title>

<script src ="https://cdn.tailwindcss.com"></script>
 <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'mont': ['Arial', 'sans-serif'],
                        'playfair': ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        'brand': '#4c4faf',
                        'brand-dark': '#3f3e8e',
                    }
                }
            }
        }
    </script>
</head>
<style>
    body,html{
        font-family:Arial, sans-serif;
    }

    
</style>


<body class="font-mont flex flex-col min-h-screen bg-gray-50">
            <main class="flex-grow py-24">
                <div class="container mx-auto px-4">
                    <div class="max-w-md mx-auto h-auto">
                        <div class="bg-white rounded-lg shadow-lg p-8 my-8">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold mb-2">Welcome Back</h2>
                                <p class="text-gray-600">Log in to access your CompareIt account</p>
                            </div>
                            
                                <!-- linking php for login -->

                            <form action= "process_login.php" method="POST" id="login-form">
                                <div class="mb-6">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Enter your email" required
                                     class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">


                                </div>
                                <div class="mb-8">
                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" id="password" name="password" placeholder="Enter your password" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                                </div>
                                <div class="flex items-center justify-between mb-8">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="remember" name="remember" 
                                        class="h-4 w-4 text-brand focus:ring-brand border-gray-300 rounded">
                                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>

                                    </div>
                                    
                                </div>
                                <button type="submit" 
                                class="w-full bg-brand text-white py-3 px-4 rounded-md hover:bg-brand-dark transition duration-200">

                                Login
                            </button>
                            </form>
                            <div class="text-center mt-8">
                            <!-- linking signup page -->

                                <p class="text-sm text-gray-600">Don't have an account? <a href="signup.php"  class="text-brand font-medium">Sign up</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

    <?php
        include 'footer.php';

        echo '<script src="js/login.js"></script>';
    ?>
  
 </body>
 
