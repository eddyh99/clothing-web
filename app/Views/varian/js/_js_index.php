<script nonce="<?= esc($nonce) ?>">
    $('#tabel_varian').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/varian/show_varian",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_varian() ===", response);

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
                data: 'sku'
            },
            {
                data: 'product_name'
            },
            {
                data: 'size'
            },
            {
                data: 'current_stock'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/varian/update/${full.variant_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-varian" data-id="${full.variant_id}" data-name="${full.product_name}" data-bs-toggle="modal" data-bs-target="#modal_deletevarian"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data varian tersedia."
        }
    });

    $(function() {
        // Klik tombol delete varian
        $(document).on('click', '.btn-delete-varian', function () {
            $('#varianIdToDelete').val($(this).data('id'));
            $('#varianNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#varianIdToDelete').val();

            $.getJSON(`<?= base_url('members/varian/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletevarian').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_varian').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus varian");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus varian");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus varian");
                    }
                });
        });

    });
</script>
