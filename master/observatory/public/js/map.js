document.addEventListener('DOMContentLoaded', function () {
    var mapEl = document.getElementById('map');
    if (!mapEl || typeof L === 'undefined' || !window.OBSERVATORY_MAP_API) {
        return;
    }

    var map = L.map('map').setView([52.0, 19.0], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    fetch(window.OBSERVATORY_MAP_API)
        .then(function (r) { return r.json(); })
        .then(function (stations) {
            stations.forEach(function (station) {
                var plans = station.plans || [];
                var popup = '<strong>' + escapeHtml(station.name) + '</strong><br>';
                popup += 'Właściciel: ' + escapeHtml(station.owner_name) + '<br>';
                popup += 'Wys.: ' + station.altitude_m + ' m<br><br>';
                if (plans.length === 0) {
                    popup += '<em>Brak planów na dziś</em>';
                } else {
                    popup += '<strong>Plan na dziś:</strong><ul style="margin:4px 0;padding-left:16px">';
                    plans.forEach(function (p) {
                        popup += '<li>' + escapeHtml(p.designation) + ' (' + escapeHtml(p.planned_start_utc) + ' UTC)</li>';
                    });
                    popup += '</ul>';
                }
                L.marker([parseFloat(station.latitude), parseFloat(station.longitude)])
                    .addTo(map)
                    .bindPopup(popup);
            });
        })
        .catch(function () {
            mapEl.innerHTML = '<p style="padding:20px;color:#fff">Nie udało się załadować danych mapy.</p>';
        });

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }
});
