<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script nonce="<?= esc($nonce) ?>" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script nonce="<?= esc($nonce) ?>">
   $(function() {
    var $countrySelect = $('#country');
    var selectedCountry = $('#selected_country').val() || '';

    if (!$countrySelect.length) {
        console.error('Elemen #country tidak ditemukan di halaman.');
        return;
    }

    $.getJSON('<?= base_url('assets/json/country-list.json') ?>')
        .done(function(data) {
            $countrySelect.empty().append('<option value="">Pilih Negara</option>');

            $.each(data, function(index, item) {
                var $option = $('<option></option>')
                    .val(item.countryName)
                    .text(item.countryName);

                if (item.countryName === selectedCountry) {
                    $option.prop('selected', true);
                }

                $countrySelect.append($option);
            });

            // Inisialisasi Select2 setelah options terisi
            $countrySelect.select2();
        })
        .fail(function(jqxhr, textStatus, error) {
            console.error('Gagal load country-list.json:', error);
            alert('Gagal memuat daftar negara.');
        });
});

</script>