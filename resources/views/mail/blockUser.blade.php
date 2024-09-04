<!DOCTYPE html>
<html>

<head>
    <title>Your OTP Code</title>
</head>

<body>
    <h1>Hello, {{ $user->name }}!</h1>
    <h1>Your Account Has Been Blocked as u tried to log in many times</h1>
    <p>we think that a person not u tried to log in withi your mail : {{ $user->email }}.</p>

    <p>To un block account you Should Resset Your Password Follow This Link <a href="#">Reset</a> </p>
</body>

</html>
