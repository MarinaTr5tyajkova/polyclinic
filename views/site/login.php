<?php
if (!\Src\Auth\Auth::check()):
    ?>
    <h2>Авторизация</h2>
    <h3><?= $message ?? ''; ?></h3>

    <form method="post" class="login-form">
        <label>Логин <input type="text" name="login"></label>
        <label>Пароль <input type="password" name="password"></label>
        <button class="btn-login">Войти</button>
    </form>
<?php endif; ?>