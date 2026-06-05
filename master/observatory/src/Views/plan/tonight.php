<h1>Plan obserwacji na dziś</h1>
<p class="subtitle">Noc: <strong><?= e($nightDate) ?></strong> | Stacja: <strong><?= e($station['name']) ?></strong></p>

<div class="grid-2">
    <section class="panel form-panel">
        <h2>Dodaj plan obserwacji</h2>
        <form method="post" action="<?= e(baseUrl('plan/tonight')) ?>">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <label>Zdarzenie (planetoida)
                <select name="event_id" required>
                    <option value="">— wybierz —</option>
                    <?php foreach ($events as $ev): ?>
                        <option value="<?= (int) $ev['id'] ?>">
                            <?= e($ev['designation']) ?> — <?= e($ev['star_designation']) ?> @ <?= e($ev['predicted_time_utc']) ?> UTC
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Start obserwacji (UTC)
                <input type="datetime-local" name="planned_start_utc" required>
            </label>
            <label>Koniec obserwacji (UTC)
                <input type="datetime-local" name="planned_end_utc" required>
            </label>
            <label>Notatki
                <textarea name="notes" rows="2"></textarea>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="force" value="1"> Wymuś zapis mimo konfliktu
            </label>
            <button type="submit" class="btn">Zapisz plan</button>
        </form>
        <a class="btn btn-secondary" href="<?= e(baseUrl('plan/export')) ?>">Eksportuj plan nocy (CSV)</a>
    </section>

    <section class="panel">
        <h2>Sugerowane wolne planetoidy</h2>
        <p class="hint">Planetoidy bez przypisanej stacji na dziś — koordynacja obserwacji.</p>
        <?php if (empty($suggestions)): ?>
            <p>Brak wolnych zdarzeń na dziś.</p>
        <?php else: ?>
            <ul class="suggestion-list">
            <?php foreach ($suggestions as $s): ?>
                <li>
                    <strong><?= e($s['designation']) ?></strong>
                    — <?= e($s['star_designation']) ?>
                    @ <?= e(formatUtcLocal($s['predicted_time_utc'])) ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</div>

<section class="panel">
    <h2>Moje plany na dziś</h2>
    <?php if (empty($myPlans)): ?>
        <p>Nie masz jeszcze planów na tę noc.</p>
    <?php else: ?>
        <table class="data-table">
            <thead><tr><th>Planetoida</th><th>Gwiazda</th><th>Okno UTC</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($myPlans as $p): ?>
                <tr>
                    <td><?= e($p['designation']) ?></td>
                    <td><?= e($p['star_designation']) ?></td>
                    <td><?= e($p['planned_start_utc']) ?> – <?= e($p['planned_end_utc']) ?></td>
                    <td><span class="badge"><?= e(statusLabel($p['status'])) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Wszystkie stacje — plan na dziś (koordynacja)</h2>
    <table class="data-table">
        <thead><tr><th>Stacja</th><th>Planetoida</th><th>Okno UTC</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($allPlans as $p): ?>
            <tr class="<?= $p['station_id'] == $station['id'] ? 'highlight-row' : '' ?>">
                <td><?= e($p['station_name']) ?></td>
                <td><?= e($p['designation']) ?></td>
                <td><?= e($p['planned_start_utc']) ?> – <?= e($p['planned_end_utc']) ?></td>
                <td><span class="badge"><?= e(statusLabel($p['status'])) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
