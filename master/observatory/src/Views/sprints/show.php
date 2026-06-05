<h1><?= e($sprint['title']) ?></h1>
<section class="panel">
    <p><strong>Cel:</strong> <a href="<?= e(baseUrl('objects/' . $sprint['target_object_id'])) ?>"><?= e($sprint['designation']) ?></a></p>
    <p><strong>Status:</strong> <span class="badge"><?= e(statusLabel($sprint['status'])) ?></span></p>
    <p><strong>Okres:</strong> <?= e(formatUtcLocal($sprint['start_utc'])) ?> – <?= e(formatUtcLocal($sprint['end_utc'])) ?></p>
    <p><strong>Organizator:</strong> <?= e($sprint['creator_name']) ?></p>
    <?php if ($sprint['description']): ?>
        <p><?= e($sprint['description']) ?></p>
    <?php endif; ?>

    <?php if (\App\Helpers\Auth::check() && !$registered && in_array($sprint['status'], ['upcoming', 'active'], true)): ?>
        <form method="post" action="<?= e(baseUrl('sprints/' . $sprint['id'] . '/register')) ?>">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <button type="submit" class="btn">Dołącz do sprintu</button>
        </form>
    <?php elseif ($registered): ?>
        <p class="success-text">✓ Twoja stacja jest zapisana do tego sprintu.</p>
    <?php endif; ?>

    <?php if ($sprint['status'] === 'completed'): ?>
        <a class="btn" href="<?= e(baseUrl('sprints/' . $sprint['id'] . '/summary')) ?>">Zobacz podsumowanie</a>
    <?php endif; ?>
</section>

<?php if ($registered && in_array($sprint['status'], ['upcoming', 'active', 'completed'], true)): ?>
<section class="panel form-panel">
    <h2>Dodaj zdjęcie do sprintu</h2>
    <form method="post" action="<?= e(baseUrl('sprints/' . $sprint['id'] . '/photo')) ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>Podpis
            <input type="text" name="caption" placeholder="Opis zdjęcia">
        </label>
        <label>Zdjęcie (JPEG/PNG)
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
        </label>
        <button type="submit" class="btn">Prześlij</button>
    </form>
</section>
<?php endif; ?>

<section class="panel">
    <h2>Uczestnicy (<?= count($participants) ?>)</h2>
    <?php if (empty($participants)): ?>
        <p>Brak zapisanych stacji.</p>
    <?php else: ?>
        <ul class="participant-list">
        <?php foreach ($participants as $p): ?>
            <li>
                <a href="<?= e(baseUrl('stations/' . $p['station_id'])) ?>"><?= e($p['station_name']) ?></a>
                — zapisano: <?= e($p['registered_at']) ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
