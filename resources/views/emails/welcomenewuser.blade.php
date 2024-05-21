<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Platform</title>
</head>
<body>
    <h1>Welcome, {{ $user->fname }}!</h1>
    <p>We are excited to have you on board. Here are your details:</p>
    <ul>
        <li>Username: {{ $user->username }}</li>
        <li>Email: {{ $user->email }}</li>
        <li>Password: {{ $plaintextPassword }}</li>
    </ul>
    <p>Please login into the system <a href="https://spmr.mygovuc.gov.my">System</a> to update your account Password at Account Detail.<br>If you have any questions, feel free to reach out to us.</p>
    <p>Thank you,</p>
</body>
</html>
