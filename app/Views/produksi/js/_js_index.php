<script nonce="<?= esc($nonce) ?>">
    $('#tabel_produksi').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/produksi/show_produksi",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_produksi() ===", response);

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
                data: 'quantity_target'
            },
            {
                data: 'status'
            },
            {
                data: 'started_at'
            },
            {
                data: 'completed_at'
            },            
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/produksi/update/${full.prod_order_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    return btnedit;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data produksi tersedia."
        }
    });
</script>
