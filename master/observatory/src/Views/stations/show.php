<h1><?= e($station['name']) ?></h1>
<section class="panel">
    <p><strong>Właściciel:</strong> <?= e($station['owner_name']) ?> (<?= e($station['owner_email']) ?>)</p>
    <p><strong>Lokalizacja:</strong> <?= e((string) $station['latitude']) ?>, <?= e((string) $station['longitude']) ?> — <?= (int) $station['altitude_m'] ?> m n.p.m.</p>
    <?php if ($station['equipment']): ?>
        <p><strong>Sprzęt:</strong> <?= e($station['equipment']) ?></p>
    <?php endif; ?>
    <?php if ($station['description']): ?>
        <p><?= e($station['description']) ?></p>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Historia obserwacji</h2>
    <?php if (empty($observations)): ?>
        <p>Brak zarejestrowanych obserwacji.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr><th>Data UTC</th><th>Obiekt</th><th>Wynik</th><th>Notatki</th></tr>
            </thead>
            <tbody>
            <?php foreach ($observations as $obs): ?>
                <tr>
                    <td><?= e(formatUtcLocal($obs['observed_at_utc'])) ?></td>
                    <td><a href="<?= e(baseUrl('objects/' . $obs['object_id'])) ?>"><?= e($obs['designation']) ?></a></td>
                    <td><span class="badge badge-<?= e($obs['result']) ?>"><?= e(resultLabel($obs['result'])) ?></span></td>
                    <td><?= e($obs['notes'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Galeria zdjęć</h2>
    <?php if (empty($photos)): ?>
        <p>Brak zdjęć.</p>
    <?php else: ?>
        <div class="photo-gallery">
        <?php foreach ($photos as $photo): ?>
            <a href="<?= e(assetUrl('uploads/photos/' . $photo['file_path'])) ?>" target="_blank" class="photo-thumb">
                <img src="<?= e(assetUrl('uploads/photos/' . $photo['file_path'])) ?>" alt="<?= e($photo['caption'] ?? 'Zdjęcie') ?>">
                <?php if (!empty($photo['designation'])): ?>
                    <span><?= e($photo['designation']) ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
