<h1>Centrum Planowania Obserwacji</h1>
<p class="subtitle">Koordynacja obserwacji zakryć planetoid — noc: <strong><?= e($nightDate) ?></strong></p>

<div class="grid-2">
    <section class="panel">
        <h2>Nadchodzące zdarzenia</h2>
        <?php if (empty($events)): ?>
            <p>Brak nadchodzących zdarzeń.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Planetoida</th><th>Gwiazda</th><th>Czas UTC</th><th>Odliczanie</th></tr>
                </thead>
                <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><a href="<?= e(baseUrl('objects/' . $event['object_id'])) ?>"><?= e($event['designation']) ?></a></td>
                        <td><?= e($event['star_designation']) ?></td>
                        <td><?= e(formatUtcLocal($event['predicted_time_utc'])) ?></td>
                        <td><span class="countdown" data-utc="<?= e($event['predicted_time_utc']) ?>">...</span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <section class="panel">
        <h2>Plan na dziś (<?= count($plans) ?> wpisów)</h2>
        <?php if (empty($plans)): ?>
            <p>Brak planów na dzisiejszą noc.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Stacja</th><th>Planetoida</th><th>Okno UTC</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><a href="<?= e(baseUrl('stations/' . $plan['station_id'])) ?>"><?= e($plan['station_name']) ?></a></td>
                        <td><?= e($plan['designation']) ?></td>
                        <td><?= e($plan['planned_start_utc']) ?> – <?= e($plan['planned_end_utc']) ?></td>
                        <td><span class="badge"><?= e(statusLabel($plan['status'])) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <a class="btn" href="<?= e(baseUrl('plan/export')) ?>">Eksportuj CSV</a>
        <?php endif; ?>
    </section>
</div>

<section class="panel">
    <h2>Aktywne sprinty</h2>
    <?php if (empty($sprints)): ?>
        <p>Brak aktywnych sprintów. <a href="<?= e(baseUrl('sprints/create')) ?>">Utwórz sprint</a></p>
    <?php else: ?>
        <div class="card-grid">
        <?php foreach ($sprints as $sprint): ?>
            <div class="card">
                <h3><a href="<?= e(baseUrl('sprints/' . $sprint['id'])) ?>"><?= e($sprint['title']) ?></a></h3>
                <p>Cel: <?= e($sprint['designation']) ?></p>
                <p>Status: <span class="badge"><?= e(statusLabel($sprint['status'])) ?></span></p>
                <p>Uczestnicy: <?= (int) $sprint['participants'] ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Stacje obserwacyjne (<?= count($stations) ?>)</h2>
    <p><a class="btn" href="<?= e(baseUrl('map')) ?>">Zobacz na mapie</a>
       <a class="btn" href="<?= e(baseUrl('stations')) ?>">Lista stacji</a></p>
</section>
