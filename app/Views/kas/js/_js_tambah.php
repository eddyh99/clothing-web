<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script nonce="<?= esc($nonce) ?>" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script nonce="<?= esc($nonce) ?>">
    $("#currency").select2();
    
    $(document).ready(function() {
    // Handle form submission
        $('form').on('submit', function(e) {
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Jika tombol sudah disabled, hentikan aksi
            if ($submitBtn.prop('disabled')) {
                e.preventDefault();
                return false;
            }
            
            // Disable tombol submit
            $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
            
            return true;
        });
    });
</script>