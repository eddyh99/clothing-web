<script nonce="<?= esc($nonce) ?>">
    // Menangani Show/Hide Password
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('mdi-eye-outline', 'mdi-eye-off-outline');
            } else {
                input.type = 'password';
                icon.classList.replace('mdi-eye-off-outline', 'mdi-eye-outline');
            }
        });
    });
</script>