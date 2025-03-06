<script nonce="{{ $nonce }}">
    const notyf = new Notyf({
        duration: 10000,
        position: {
            x: 'right',
            y: 'top'
        },
        types: [{
                type: 'success',
                background: 'var(--success-color, #28a745)',
            },
            {
                type: 'error',
                background: 'var(--error-color, #dc3545)',
                icon: false,
            },
        ],
    });

    @if (session('status'))
        notyf.success("{{ session('status') }}");
    @endif

    @if (session('error'))
        notyf.error("{{ session('error') }}");
    @endif
</script>
