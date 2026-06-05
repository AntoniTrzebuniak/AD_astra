<?php $s = $summary['sprint']; ?>
<h1>Podsumowanie sprintu</h1>
<h2><?= e($s['title']) ?></h2>

<section class="panel">
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= $summary['participant_count'] ?></span>
            <span class="stat-label">Uczestników</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $summary['photo_count'] ?></span>
            <span class="stat-label">Zdjęć</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= e($s['designation']) ?></span>
            <span class="stat-label">Planetoida</span>
        </div>
    </div>
    <p><strong>Okres:</strong> <?= e(formatUtcLocal($s['start_utc'])) ?> – <?= e(formatUtcLocal($s['end_utc'])) ?></p>
    <?php if ($s['description']): ?>
        <p><?= e($s['description']) ?></p>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Uczestniczące stacje</h2>
    <ul class="participant-list">
    <?php foreach ($summary['participants'] as $p): ?>
        <li><a href="<?= e(baseUrl('stations/' . $p['station_id'])) ?>"><?= e($p['station_name']) ?></a></li>
    <?php endforeach; ?>
    </ul>
</section>

<section class="panel">
    <h2>Zdjęcia ze sprintu</h2>
    <?php if (empty($summary['photos'])): ?>
        <p>Brak przesłanych zdjęć.</p>
    <?php else: ?>
        <div class="photo-gallery">
        <?php foreach ($summary['photos'] as $photo): ?>
            <a href="<?= e(assetUrl('uploads/photos/' . $photo['file_path'])) ?>" target="_blank" class="photo-thumb">
                <img src="<?= e(assetUrl('uploads/photos/' . $photo['file_path'])) ?>" alt="<?= e($photo['caption'] ?? '') ?>">
                <span><?= e($photo['station_name'] ?? '') ?></span>
            </a>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
