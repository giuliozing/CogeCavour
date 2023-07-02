<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="/login/create" method="POST">
        {{ csrf_field() }}
        <input type="email" name="email" placeholder="Email">
        @if ($errors->any())
            {{ $errors->first('email') }}
        @endif
        <input type="password" name="password" placeholder="Password">
        @if ($errors->any())
            {{ $errors->first('password') }}
        @endif
        <input type="submit" value="Login">
        @if ($errors->any())
            {{ $errors->first('userNotFound') }}
        @endif
    </form>
</body>
</html>