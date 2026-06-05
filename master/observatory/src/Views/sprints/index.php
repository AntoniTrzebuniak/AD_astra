<h1>Sprinty obserwacyjne</h1>
<p class="subtitle">Wydarzenia masowej obserwacji — jak najwięcej stacji, jedna planetoida.</p>

<?php if (\App\Helpers\Auth::check()): ?>
    <a class="btn" href="<?= e(baseUrl('sprints/create')) ?>">Utwórz nowy sprint</a>
<?php endif; ?>

<?php if (!empty($active)): ?>
<section class="panel">
    <h2>Aktywne sprinty</h2>
    <div class="card-grid">
    <?php foreach ($active as $s): ?>
        <div class="card card-active">
            <h3><a href="<?= e(baseUrl('sprints/' . $s['id'])) ?>"><?= e($s['title']) ?></a></h3>
            <p>Cel: <?= e($s['designation']) ?></p>
            <p><?= e(formatUtcLocal($s['start_utc'])) ?> – <?= e(formatUtcLocal($s['end_utc'])) ?></p>
            <p>Uczestnicy: <?= (int) $s['participants'] ?></p>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($upcoming)): ?>
<section class="panel">
    <h2>Nadchodzące sprinty</h2>
    <div class="card-grid">
    <?php foreach ($upcoming as $s): ?>
        <div class="card">
            <h3><a href="<?= e(baseUrl('sprints/' . $s['id'])) ?>"><?= e($s['title']) ?></a></h3>
            <p>Cel: <?= e($s['designation']) ?></p>
            <p>Start: <?= e(formatUtcLocal($s['start_utc'])) ?></p>
            <p>Uczestnicy: <?= (int) $s['participants'] ?></p>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="panel">
    <h2>Archiwum sprintów</h2>
    <?php if (empty($completed)): ?>
        <p>Brak zakończonych sprintów.</p>
    <?php else: ?>
        <table class="data-table">
            <thead><tr><th>Tytuł</th><th>Cel</th><th>Okres</th><th>Uczestnicy</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($completed as $s): ?>
                <tr>
                    <td><?= e($s['title']) ?></td>
                    <td><?= e($s['designation']) ?></td>
                    <td><?= e(formatUtcLocal($s['start_utc'])) ?></td>
                    <td><?= (int) $s['participants'] ?></td>
                    <td><a href="<?= e(baseUrl('sprints/' . $s['id'] . '/summary')) ?>">Podsumowanie</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
