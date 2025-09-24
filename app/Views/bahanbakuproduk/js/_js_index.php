<script nonce="<?= esc($nonce) ?>">
    $('#tabel_bahanbakuproduk').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/bahanbakuproduk/show_bahanbakuproduk",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_bahanbakuproduk() ===", response);

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
                data: 'product_name'
            },
            {
                data: 'barcode'
            },
            {
                data: 'material_name'
            },
            {
                data: 'quantity'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/bahanbakuproduk/update/${full.product_raw_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-bahanbakuproduk" data-id="${full.product_raw_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletebahanbakuproduk"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data bahanbakuproduk tersedia."
        }
    });

    $(function() {
        // Klik tombol delete bahanbakuproduk
        $(document).on('click', '.btn-delete-bahanbakuproduk', function () {
            $('#bahanbakuprodukIdToDelete').val($(this).data('id'));
            $('#bahanbakuprodukNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#bahanbakuprodukIdToDelete').val();

            $.getJSON(`<?= base_url('members/bahanbakuproduk/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletebahanbakuproduk').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_bahanbakuproduk').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus bahanbakuproduk");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus bahanbakuproduk");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus bahanbakuproduk");
                    }
                });
        });

    });
</script>
