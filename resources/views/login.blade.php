<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BreadSaver</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; text-align: center; }
        form { display: inline-block; text-align: left; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        input { width: 100%; padding: 8px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>🔑 Login BreadSaver</h2>
    <form action="" method="POST">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Masuk</button>
    </form>
    <br><br>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
</body>
</html>