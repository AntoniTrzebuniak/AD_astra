<h1>Logowanie</h1>
<section class="panel form-panel">
    <form method="post" action="<?= e(baseUrl('login')) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>Email
            <input type="email" name="email" required>
        </label>
        <label>Hasło
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn">Zaloguj</button>
    </form>
    <p>Nie masz konta? <a href="<?= e(baseUrl('register')) ?>">Zarejestruj stację</a></p>
    <p class="hint">Konto testowe admin: admin@adastra.pl / password</p>
</section>
