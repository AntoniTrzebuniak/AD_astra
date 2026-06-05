<h1>Nowy sprint obserwacyjny</h1>
<section class="panel form-panel">
    <form method="post" action="<?= e(baseUrl('sprints/create')) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>Tytuł sprintu
            <input type="text" name="title" required placeholder="np. Sprint Apophis 2026">
        </label>
        <label>Planetoida docelowa
            <select name="target_object_id" required>
                <option value="">— wybierz —</option>
                <?php foreach ($objects as $obj): ?>
                    <option value="<?= (int) $obj['id'] ?>"><?= e($obj['designation']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Opis
            <textarea name="description" rows="3" placeholder="Cel: jak najwięcej stacji obserwuje zakrycie..."></textarea>
        </label>
        <label>Start (UTC)
            <input type="datetime-local" name="start_utc" required>
        </label>
        <label>Koniec (UTC)
            <input type="datetime-local" name="end_utc" required>
        </label>
        <button type="submit" class="btn">Utwórz sprint</button>
    </form>
</section>
