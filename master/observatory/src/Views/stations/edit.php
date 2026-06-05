<h1>Moja stacja</h1>
<section class="panel form-panel">
    <form method="post" action="<?= e(baseUrl('station/edit')) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>Nazwa stacji
            <input type="text" name="name" value="<?= e($station['name']) ?>" required>
        </label>
        <label>Szerokość geograficzna
            <input type="number" name="latitude" step="0.0000001" value="<?= e((string) $station['latitude']) ?>" required>
        </label>
        <label>Długość geograficzna
            <input type="number" name="longitude" step="0.0000001" value="<?= e((string) $station['longitude']) ?>" required>
        </label>
        <label>Wysokość n.p.m. (m)
            <input type="number" name="altitude_m" value="<?= (int) $station['altitude_m'] ?>">
        </label>
        <label>Sprzęt
            <textarea name="equipment" rows="3"><?= e($station['equipment'] ?? '') ?></textarea>
        </label>
        <label>Opis
            <textarea name="description" rows="3"><?= e($station['description'] ?? '') ?></textarea>
        </label>
        <button type="submit" class="btn">Zapisz</button>
    </form>
</section>
