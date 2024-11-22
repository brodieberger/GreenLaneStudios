<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Check if the user is logged in
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hawk Island Marina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-container {
            display: none;
        }

        .link {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Hawk Island Marina</h2>

        <?php if ($isLoggedIn): ?>
            <!-- Display options for logged-in users -->
            <div class="text-center">
                <h4>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h4>
                <div class="btn-container">
                    <button onclick="showForm('changePasswordForm')" class="btn btn-warning">Change Password</button>
                    <form action="logout.php" method="post" style="display: inline;">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div id="changePasswordForm" class="form-container">
                    <form action="change_password_handler.php" method="post">
                        <h4>Change Password</h4>
                        <div class="mb-3">
                            <label for="old-password" class="form-label">Current Password:</label>
                            <input type="password" id="old-password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new-password" class="form-label">New Password:</label>
                            <input type="password" id="new-password" name="new_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Change Password</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Display options for non-logged-in users -->
            <div class="btn-container">
                <button onclick="showForm('loginForm')" class="btn btn-primary">Login</button>
                <button onclick="showForm('registerForm')" class="btn btn-secondary">Create Account</button>
                <button onclick="showForm('forgotPasswordForm')" class="btn btn-link">Forgot Password?</button>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Login Form -->
                    <div id="loginForm" class="form-container">
                        <form action="login_handler.php" method="post">
                            <h4>Login</h4>
                            <div class="mb-3">
                                <label for="login-email" class="form-label">Email:</label>
                                <input type="email" id="login-email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password:</label>
                                <input type="password" id="login-password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div id="registerForm" class="form-container">
                        <form action="register_handler.php" method="post">
                            <h4>Create Account</h4>
                            <div class="mb-3">
                                <label for="register-email" class="form-label">Email:</label>
                                <input type="email" id="register-email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="register-name" class="form-label">First Name:</label>
                                <input type="text" id="register-name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="register-password" class="form-label">Password:</label>
                                <input type="password" id="register-password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-secondary w-100">Create Account</button>
                        </form>
                    </div>

                    <!-- Forgot Password Form -->
                    <div id="forgotPasswordForm" class="form-container">
                        <form action="forgot_password_handler.php" method="post">
                            <h4>Forgot Password</h4>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Request Password Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function showForm(formId) {
            const forms = ['loginForm', 'registerForm', 'changePasswordForm', 'forgotPasswordForm'];
            forms.forEach(id => {
                document.getElementById(id).style.display = (id === formId) ? 'block' : 'none';
            });
        }
        window.onload = function () {
            showForm('loginForm');
        };
    </script>
    </script>
</body>

</html>