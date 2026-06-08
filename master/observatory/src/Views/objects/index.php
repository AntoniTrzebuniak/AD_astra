<h1>Obiekty obserwowane</h1>
<section class="panel">
<table class="data-table">
    <thead>
        <tr><th>Oznaczenie</th><th>Nazwa</th><th>Typ</th><th>Jasność</th><th>Średnica</th><th>Nadchodzące</th></tr>
    </thead>
    <tbody>
    <?php foreach ($objects as $obj): ?>
        <tr>
            <td><a href="<?= e(baseUrl('objects/' . $obj['id'])) ?>"><?= e($obj['designation']) ?></a></td>
            <td><?= e($obj['name'] ?? '—') ?></td>
            <td><?= e($obj['type']) ?></td>
            <td><?= $obj['magnitude'] !== null ? e((string) $obj['magnitude']) : '—' ?></td>
            <td><?= $obj['diameter_km'] !== null ? e((string) $obj['diameter_km']) . ' km' : '—' ?></td>
            <td><?= (int) $obj['upcoming_events'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</section>
