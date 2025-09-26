<script nonce="<?= esc($nonce) ?>">
    $('#tabel_penjualan').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/penjualan/show_penjualan",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_penjualan() ===", response);

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
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/penjualan/update/${full.category_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-penjualan" data-id="${full.category_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletepenjualan"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data penjualan tersedia."
        }
    });

    $(function() {
        // Klik tombol delete penjualan
        $(document).on('click', '.btn-delete-penjualan', function () {
            $('#penjualanIdToDelete').val($(this).data('id'));
            $('#penjualanNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#penjualanIdToDelete').val();

            $.getJSON(`<?= base_url('members/penjualan/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletepenjualan').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_penjualan').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus penjualan");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus penjualan");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus penjualan");
                    }
                });
        });

    });
</script>
