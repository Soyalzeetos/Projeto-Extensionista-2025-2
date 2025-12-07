<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Autenticando...</title>
</head>

<body>
    <script>
        const user = <?= $userDataJson ?>;
        localStorage.setItem('user_session', JSON.stringify(user));
        window.location.href = '/';
    </script>
</body>

</html>
