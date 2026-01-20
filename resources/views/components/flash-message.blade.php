@props([])

@php
$flashTypes = [
    'success' => ['icon' => 'success', 'title' => 'Success'],
    'info' => ['icon' => 'info', 'title' => 'Info'],
    'warning' => ['icon' => 'warning', 'title' => 'Warning'],
    'error' => ['icon' => 'error', 'title' => 'Error'],
];

$flashMessages = [];
foreach ($flashTypes as $type => $meta) {
    if (session($type)) {
        $flashMessages[] = [
            'icon' => $meta['icon'],
            'title' => $meta['title'],
            'text' => session($type),
        ];
    }
}
@endphp

@if (count($flashMessages))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Swal) {
                return;
            }
            const messages = @json($flashMessages);
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toastEl) => {
                    toastEl.addEventListener('mouseenter', Swal.stopTimer);
                    toastEl.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            messages.forEach((message, index) => {
                setTimeout(() => toast.fire(message), index * 250);
            });
        });
    </script>
@endif
