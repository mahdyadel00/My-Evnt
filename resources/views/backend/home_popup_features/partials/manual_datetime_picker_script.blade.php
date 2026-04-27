@php
    $syncOnLoad = $syncOnLoad ?? false;
@endphp
<script>
    (function (pickerId, textId, syncOnLoad) {
        function pad2(n) {
            return String(n).padStart(2, '0');
        }

        function formatFromLocal(isoLocal) {
            if (!isoLocal) {
                return '';
            }
            var d = new Date(isoLocal);
            if (isNaN(d.getTime())) {
                return '';
            }
            try {
                return new Intl.DateTimeFormat(document.documentElement.lang || 'en', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                }).format(d);
            } catch (e) {
                return d.toString();
            }
        }

        function toLocalValue(d) {
            return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate()) + 'T' + pad2(d.getHours()) +
                ':' + pad2(d.getMinutes());
        }

        function trySyncPickerFromLabel(picker, textEl) {
            if (!picker || !textEl) {
                return;
            }
            var t = String(textEl.value || '').trim();
            if (!t) {
                picker.value = '';
                return;
            }
            var ms = Date.parse(t);
            if (!isNaN(ms)) {
                picker.value = toLocalValue(new Date(ms));
            } else {
                picker.value = '';
            }
        }

        function init() {
            var p = document.getElementById(pickerId);
            var t = document.getElementById(textId);
            if (!p || !t) {
                return;
            }
            p.addEventListener('change', function () {
                if (p.value) {
                    t.value = formatFromLocal(p.value);
                }
            });
            if (syncOnLoad) {
                trySyncPickerFromLabel(p, t);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })(@json($pickerId), @json($textId), @json($syncOnLoad));
</script>
