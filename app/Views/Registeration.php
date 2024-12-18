<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .password-container {
            position: relative;
        }

        #togglepassword {
            position: absolute;
            right: 10px;
            top: 10px;
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
        }

        .error {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
            text-align: center;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .center{
            text-align: center;
        }
        .login-link{
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="form-title">User Registration</h2>
        <form action="/postregister" method="POST">
            <div class="form-group">
                <!-- <label for="name">Name</label> -->
                <input type="text" id="name" name="name" value="<?= old('name') ?>" placeholder="Enter Username">
                <div class="error"><?= session()->getFlashdata('nameerror') ?></div>
            </div>

            <div class="form-group">
                <!-- <label for="email">Email</label> -->
                <input type="text" id="email" name="email" value="<?= old('email') ?>" placeholder="Enter Email">
                <div class="error"><?= session()->getFlashdata('emailerr') ?></div>
            </div>

            <div class="form-group password-container">
                <!-- <label for="password">Password</label> -->
                <input type="password" id="password" name="password" value="<?= old('password') ?>" placeholder="Enter Password">
                <button id="togglepassword" type="button">Show</button>
                <div id="errorMessages" class="error">
                    <?php if (session()->has('passworderr')): ?>
                        <?php $error = session('passworderr')['password'];
                        $errorMessage = explode(', ', $error) ?>
                        <?php foreach ($errorMessage as $err): ?>
                            <p><?= $err ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <!-- <label for="confirm_password">Confirm Password</label> -->
                <input type="password" id="confirm_password" name="confirm_password" value="<?= old('confirm_password') ?>" placeholder="Enter Confirm Password">
                <div class="error"><?= session()->getFlashdata('confirmerror') ?></div>
            </div>

            <div class="form-group">
                <input type="submit" value="Register" class="submit-btn">
                <div class="error"><?= session()->getFlashdata('error') ?></div>
                <div class="error"><?= session()->getFlashdata('uniqueerror') ?></div>
            </div>

            <div class="form-group center">
                Already have an account? <a href="/login" class="login-link">Login</a>
            </div>
        </form>
    </div>

    <script>
        const togglebutton = document.getElementById('togglepassword')
        const password = document.getElementById('password')

        togglebutton.addEventListener('click', (event) => {
            event.preventDefault()
            var type = password.type === 'password' ? 'text' : 'password';
            password.type = type
            togglebutton.textContent = type == 'password' ? 'Show' : 'Hide'
        })
    </script>
</body>
</html>