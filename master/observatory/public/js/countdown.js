document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.countdown').forEach(function (el) {
        var target = new Date(el.dataset.utc + 'Z');
        function tick() {
            var now = new Date();
            var diff = target - now;
            if (diff <= 0) {
                el.textContent = 'TERAZ!';
                return;
            }
            var h = Math.floor(diff / 3600000);
            var m = Math.floor((diff % 3600000) / 60000);
            var s = Math.floor((diff % 60000) / 1000);
            el.textContent = h + 'h ' + m + 'm ' + s + 's';
        }
        tick();
        setInterval(tick, 1000);
    });
});
