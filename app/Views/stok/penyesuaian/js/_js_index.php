<script nonce="<?= esc($nonce) ?>">
    $('#tabel_stokpenyesuaian').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/stok/penyesuaian/show_stok_penyesuaian",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_stokpenyesuaian() ===", response);

                // Pastikan response.data adalah array
                if (Array.isArray(response.data)) {
                    return response.data;
                }
                return [];
            },
            "error": function(xhr, error, thrown) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } catch (e) {
                    console.error('Error parsing response', e);
                }
            }
        },
        "columns": [
            {
                data: null,
                render: function(data, type, full, meta) {
                    return meta.row + 1;
                },
                orderable: false,
                searchable: false,
                width: "30px"
            },
            {
                data: 'variant_id'
            },
            {
                data: 'quantity'
            },
            {
                data: 'reason'
            },
            {
                data: 'status'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnapprove = `<a href="<?= base_url() ?>members/stokpenyesuaian/approve/${full.adjustment_id}" class="btn btn-sm btn-success rounded-2"><i class="mdi mdi-check-bold"></i></a>`;
                    const btnreject = `<a href="#" class="btn btn-sm btn-danger btn-delete-stokpenyesuaian" data-id="${full.adjustment_id}" data-name="${full.adjustment_id}" data-bs-toggle="modal" data-bs-target="#modal_deletestokpenyesuaian"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnapprove + ' ' + btnreject;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data stok penyesuaian tersedia."
        }
    });
</script>
