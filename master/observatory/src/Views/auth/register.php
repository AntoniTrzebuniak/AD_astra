<h1>Rejestracja właściciela stacji</h1>
<section class="panel form-panel">
    <form method="post" action="<?= e(baseUrl('register')) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <h3>Dane użytkownika</h3>
        <label>Imię i nazwisko
            <input type="text" name="name" required>
        </label>
        <label>Email
            <input type="email" name="email" required>
        </label>
        <label>Hasło (min. 6 znaków)
            <input type="password" name="password" required minlength="6">
        </label>
        <h3>Dane stacji</h3>
        <label>Nazwa stacji
            <input type="text" name="station_name" required>
        </label>
        <label>Szerokość geograficzna (lat)
            <input type="number" name="latitude" step="0.0000001" required placeholder="np. 52.4064">
        </label>
        <label>Długość geograficzna (lng)
            <input type="number" name="longitude" step="0.0000001" required placeholder="np. 16.9252">
        </label>
        <label>Wysokość n.p.m. (m)
            <input type="number" name="altitude_m" value="0">
        </label>
        <label>Sprzęt
            <textarea name="equipment" rows="2" placeholder="Teleskop, kamera, VTI GPS..."></textarea>
        </label>
        <label>Opis
            <textarea name="description" rows="2"></textarea>
        </label>
        <button type="submit" class="btn">Zarejestruj</button>
    </form>
</section>
