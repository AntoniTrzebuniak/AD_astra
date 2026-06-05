<h1>Panel administratora</h1>

<div class="grid-2">
    <section class="panel form-panel">
        <h2>Dodaj obiekt</h2>
        <form method="post" action="<?= e(baseUrl('admin/object')) ?>">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <label>Oznaczenie
                <input type="text" name="designation" required placeholder="(99942) Apophis">
            </label>
            <label>Nazwa
                <input type="text" name="name">
            </label>
            <label>Typ
                <select name="type">
                    <option value="asteroid">Planetoida</option>
                    <option value="star">Gwiazda</option>
                    <option value="other">Inne</option>
                </select>
            </label>
            <label>Jasność
                <input type="number" name="magnitude" step="0.01">
            </label>
            <label>Średnica (km)
                <input type="number" name="diameter_km" step="0.001">
            </label>
            <label>Notatki
                <textarea name="notes" rows="2"></textarea>
            </label>
            <button type="submit" class="btn">Dodaj obiekt</button>
        </form>
    </section>

    <section class="panel form-panel">
        <h2>Dodaj zdarzenie zakrycia</h2>
        <form method="post" action="<?= e(baseUrl('admin/event')) ?>">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <label>Obiekt
                <select name="object_id" required>
                    <?php foreach ($objects as $obj): ?>
                        <option value="<?= (int) $obj['id'] ?>"><?= e($obj['designation']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Gwiazda (oznaczenie)
                <input type="text" name="star_designation" required>
            </label>
            <label>Jasność gwiazdy
                <input type="number" name="star_magnitude" step="0.01">
            </label>
            <label>Przewidywany czas (UTC)
                <input type="datetime-local" name="predicted_time_utc" required>
            </label>
            <label>Czas trwania (ms)
                <input type="number" name="duration_ms" value="5000">
            </label>
            <label>Niepewność ścieżki (km)
                <input type="number" name="path_uncertainty_km" step="0.01">
            </label>
            <label>Spadek jasności (mag)
                <input type="number" name="magnitude_drop" step="0.01">
            </label>
            <button type="submit" class="btn">Dodaj zdarzenie</button>
        </form>
    </section>
</div>

<section class="panel">
    <h2>Obiekty w bazie</h2>
    <table class="data-table">
        <thead><tr><th>Oznaczenie</th><th>Typ</th><th>Jasność</th></tr></thead>
        <tbody>
        <?php foreach ($objects as $obj): ?>
            <tr>
                <td><a href="<?= e(baseUrl('objects/' . $obj['id'])) ?>"><?= e($obj['designation']) ?></a></td>
                <td><?= e($obj['type']) ?></td>
                <td><?= $obj['magnitude'] !== null ? e((string) $obj['magnitude']) : '—' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section class="panel">
    <h2>Zdarzenia zakrycia</h2>
    <table class="data-table">
        <thead><tr><th>Planetoida</th><th>Gwiazda</th><th>Czas UTC</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($events as $ev): ?>
            <tr>
                <td><?= e($ev['designation']) ?></td>
                <td><?= e($ev['star_designation']) ?></td>
                <td><?= e($ev['predicted_time_utc']) ?></td>
                <td><?= e($ev['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
