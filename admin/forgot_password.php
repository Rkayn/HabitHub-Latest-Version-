

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-top: 0;
    text-align: center;
}

p {
    margin-bottom: 20px;
    text-align: center;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="email"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

.error {
    color: red;
    margin-top: 10px;
    text-align: center;
}

</style>
<body>
<div class="container mt-5">
        <h2>Forgot Password</h2>
        <p>Please enter your email address. We will send you a link to reset your password.</p>
        <form action="forgot_password_handler.php" method="POST">
            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" name="forgot_submit" class="btn btn-primary">Submit</button>
        </form>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include('includes/script.php');?>

