<h1>Rejestracja obserwacji</h1>
<section class="panel form-panel">
    <form method="post" action="<?= e(baseUrl('observations/new')) ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>Obiekt (planetoida)
            <select name="object_id" required>
                <option value="">— wybierz —</option>
                <?php foreach ($objects as $obj): ?>
                    <option value="<?= (int) $obj['id'] ?>"><?= e($obj['designation']) ?> <?= $obj['name'] ? '(' . e($obj['name']) . ')' : '' ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Data i czas obserwacji (UTC)
            <input type="datetime-local" name="observed_at_utc" value="<?= e(gmdate('Y-m-d\TH:i')) ?>" required>
        </label>
        <label>Wynik obserwacji
            <select name="result" required>
                <option value="positive">Pozytywna (zakrycie zaobserwowane)</option>
                <option value="negative">Negatywna (brak zakrycia)</option>
                <option value="clouded">Zachmurzenie</option>
                <option value="no_data">Brak danych</option>
            </select>
        </label>
        <label>Czas trwania zdarzenia (ms)
            <input type="number" name="duration_ms" placeholder="np. 3200">
        </label>
        <label>Notatki
            <textarea name="notes" rows="3"></textarea>
        </label>
        <label>Zdjęcia (JPEG/PNG, max 10 MB)
            <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple>
        </label>
        <button type="submit" class="btn">Zapisz obserwację</button>
    </form>
</section>
