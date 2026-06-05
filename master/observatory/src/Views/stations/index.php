<h1>Stacje obserwacyjne</h1>
<div class="card-grid">
<?php foreach ($stations as $station): ?>
    <div class="card">
        <h3><a href="<?= e(baseUrl('stations/' . $station['id'])) ?>"><?= e($station['name']) ?></a></h3>
        <p>Właściciel: <?= e($station['owner_name']) ?></p>
        <p>📍 <?= e((string) $station['latitude']) ?>, <?= e((string) $station['longitude']) ?> (<?= (int) $station['altitude_m'] ?> m)</p>
        <?php if ($station['equipment']): ?>
            <p class="equipment"><?= e($station['equipment']) ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
