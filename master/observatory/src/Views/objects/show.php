<h1><?= e($object['designation']) ?></h1>
<section class="panel">
    <table class="detail-table">
        <tr><th>Nazwa</th><td><?= e($object['name'] ?? '—') ?></td></tr>
        <tr><th>Typ</th><td><?= e($object['type']) ?></td></tr>
        <tr><th>Jasność</th><td><?= $object['magnitude'] !== null ? e((string) $object['magnitude']) : '—' ?></td></tr>
        <tr><th>Średnica</th><td><?= $object['diameter_km'] !== null ? e((string) $object['diameter_km']) . ' km' : '—' ?></td></tr>
        <tr><th>Notatki</th><td><?= e($object['notes'] ?? '—') ?></td></tr>
    </table>
</section>

<section class="panel">
    <h2>Zdarzenia zakrycia</h2>
    <?php if (empty($events)): ?>
        <p>Brak zdarzeń.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr><th>Gwiazda</th><th>Czas UTC</th><th>Czas trwania</th><th>Status</th></tr>
            </thead>
            <tbody>
            <?php foreach ($events as $ev): ?>
                <tr>
                    <td><?= e($ev['star_designation']) ?> (mag <?= e((string) ($ev['star_magnitude'] ?? '—')) ?>)</td>
                    <td><?= e(formatUtcLocal($ev['predicted_time_utc'])) ?></td>
                    <td><?= (int) $ev['duration_ms'] ?> ms</td>
                    <td><span class="badge"><?= e(statusLabel($ev['status'])) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Historia obserwacji</h2>
    <?php if (empty($observations)): ?>
        <p>Brak obserwacji tego obiektu.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr><th>Data</th><th>Stacja</th><th>Wynik</th><th>Notatki</th></tr>
            </thead>
            <tbody>
            <?php foreach ($observations as $obs): ?>
                <tr>
                    <td><?= e(formatUtcLocal($obs['observed_at_utc'])) ?></td>
                    <td><a href="<?= e(baseUrl('stations/' . $obs['station_id'])) ?>"><?= e($obs['station_name']) ?></a></td>
                    <td><span class="badge badge-<?= e($obs['result']) ?>"><?= e(resultLabel($obs['result'])) ?></span></td>
                    <td><?= e($obs['notes'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Zdjęcia</h2>
    <?php if (empty($photos)): ?>
        <p>Brak zdjęć.</p>
    <?php else: ?>
        <div class="photo-gallery">
        <?php foreach ($photos as $photo): ?>
            <a href="<?= e(assetUrl('uploads/photos/' . $photo['file_path'])) ?>" target="_blank" class="photo-thumb">
                <img src="<?= e(assetUrl('uploads/photos/' . $photo['file_path'])) ?>" alt="<?= e($photo['caption'] ?? '') ?>">
                <span><?= e($photo['station_name'] ?? '') ?></span>
            </a>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
