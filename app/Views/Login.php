<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url() ?>/styles/login.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css"
        integrity="sha512-MpdEaY2YQ3EokN6lCD6bnWMl5Gwk7RjBbpKLovlrH6X+DRokrPRAF3zQJl1hZUiLXfo2e9MrOt+udOnHCAmi5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"
        integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
            text-align: center;
            margin-bottom: 15px;
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

        .form-group input::placeholder {
            color: #999;
        }

        .center {
            text-align: center;
        }

        .linkr {
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="form-title">User Login</h2>
        <form action="/postlogin" method="POST">
            <?php if (session()->getFlashdata('loginerror')): ?>
                <div class="error"><?= session()->getFlashdata('loginerror') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="form-group">
                <input type="text" name="username" value="<?= old('username') ?>" placeholder="Enter email or username">
            </div>

            <div class="form-group password-container">
                <input type="password" id="password" name="password" value="<?= old('password') ?>"
                    placeholder="Enter password">
                <button id="togglepassword" type="button">Show</button>
            </div>

            <div class="form-group">
                <input type="submit" value="Login" class="submit-btn">
            </div>

            <div class="form-group center">
                Don't have an account? <a href="/register" class="linkr">Register</a>
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