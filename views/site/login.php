<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/polyclinic/public/assets/css/login.css">
    <title>Вход в систему</title>
</head>
<body class="login-page">

<div class="login-logo">
    <img src="/polyclinic/public/assets/images/logo.svg" alt="Логотип">
    <div class="logo-text">
        <div class="logo-a">Электронная регистратура</div>
        <div class="logo-b">Томской области</div>
    </div>
</div>

<!-- Центральный блок с формой -->
<div class="login-container">
    <div class="login-title">Для начала работы войдите в систему</div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form class="login-form" method="POST" action="<?= app()->route->getUrl('/login') ?>">
        <input type="hidden" name="csrf_token" value="<?= \Src\Auth\Auth::generateCSRF() ?>">

        <div class="form-group">
            <input type="text" id="login" name="login" placeholder="Логин" required class="form-input">
        </div>

        <div class="form-group">
            <input type="password" id="password" name="password" placeholder="Пароль" required class="form-input">
        </div>

        <button type="submit" class="login-button">Войти</button>
    </form>
</div>
</body>
</html>