<!-- JAVASCRIPT -->
 <!-- Layout config Js -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/js/layout.js"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/libs/simplebar/simplebar.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/libs/node-waves/waves.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/libs/feather-icons/feather.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/js/plugins.js"></script>
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/js/app.js"></script>
<script nonce="<?= esc($nonce) ?>" src="//cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="//cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="//cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="//cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script nonce="<?= esc($nonce) ?>" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- theme function -->
<script nonce="<?= esc($nonce) ?>" src="<?=base_url()?>assets/js/main.js"></script>
<script nonce="<?= esc($nonce) ?>">
    // Global AJAX error handler untuk token expiration
    $(document).ajaxComplete(function(event, xhr, settings) {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.redirect) {
                window.location.href = response.redirect;
                return;
            }
        } catch (e) {
            // Response bukan JSON, ignore
        }
    });
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
        const label = document.querySelector(`label[for="${field.id}"]`);
        if (label && !label.innerHTML.includes('*')) {
            label.innerHTML += ' <span class="text-danger">*</span>';
        }
    });

    function success_alert(message, tabel) {
        // Remove any existing success alerts to avoid stacking
        document.querySelectorAll('.alert-floating-top-right.alert-success').forEach(el => el.remove());

        const alertHtml = `
            <div class="alert alert-success alert-dismissible shadow fade show alert-floating-top-right" role="alert">
                ${message || `${tabel} berhasil dihapus.`}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', alertHtml);

        // Auto close after 4 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert-floating-top-right.alert-success');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150); // wait for fade out
            }
        }, 4000);
    }

    function failed_alert(message, tabel) {
        // Remove any existing success alerts to avoid stacking
        document.querySelectorAll('.alert-floating-top-right.alert-danger').forEach(el => el.remove());

        const alertHtml = `
            <div class="alert alert-danger alert-dismissible shadow fade show alert-floating-top-right" role="alert">
                ${message || `${tabel}`}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', alertHtml);

        // Auto close after 4 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert-floating-top-right.alert-danger');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150); // wait for fade out
            }
        }, 4000);
}

</script>
<?php
    if (isset($extra)) {
        echo view(@$extra);
    }
?>