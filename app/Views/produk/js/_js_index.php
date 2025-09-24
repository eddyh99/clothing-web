<script nonce="<?= esc($nonce) ?>">
    $('#tabel_produk').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/produk/show_produk",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_produk() ===", response);

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
                data: 'name'
            },
            {
                data: 'brand_id'
            },
            {
                data: 'category_id'
            },
            {
                data: 'description'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/produk/update/${full.product_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-produk" data-id="${full.product_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deleteproduk"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data produk tersedia."
        }
    });

    $(function() {
        // Klik tombol delete produk
        $(document).on('click', '.btn-delete-produk', function () {
            $('#produkIdToDelete').val($(this).data('id'));
            $('#produkNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#produkIdToDelete').val();

            $.getJSON(`<?= base_url('members/produk/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deleteproduk').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_produk').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus produk");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus produk");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus produk");
                    }
                });
        });

    });
</script>
