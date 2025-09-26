<script nonce="<?= esc($nonce) ?>">
    $('#tabel_stoktransfer').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/stok/transfer/show_stok_transfer",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_stoktransfer() ===", response);

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
                data: 'from_branch_id'
            },
            {
                data: 'to_branch_id'
            },
            {
                data: 'status'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/stoktransfer/update/${full.category_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-stoktransfer" data-id="${full.category_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletestoktransfer"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data stoktransfer tersedia."
        }
    });
</script>
