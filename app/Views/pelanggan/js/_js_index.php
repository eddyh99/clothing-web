<script nonce="<?= esc($nonce) ?>">
    $('#tabel_pelanggan').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/pelanggan/show_pelanggan",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_pelanggan() ===", response);

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
                data: 'customer_id'
            },
            {
                data: 'full_name'
            },
            {
                data: 'phone'
            },
            {
                data: 'email'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/pelanggan/update/${full.customer_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-pelanggan" data-id="${full.customer_id}" data-name="${full.full_name}" data-bs-toggle="modal" data-bs-target="#modal_deletepelanggan"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data pelanggan tersedia."
        }
    });

    $(function() {
        // Klik tombol delete pelanggan
        $(document).on('click', '.btn-delete-pelanggan', function () {
            $('#pelangganIdToDelete').val($(this).data('id'));
            $('#pelangganNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#pelangganIdToDelete').val();

            $.getJSON(`<?= base_url('members/pelanggan/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletepelanggan').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_pelanggan').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus pelanggan");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus pelanggan");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus pelanggan");
                    }
                });
        });

    });
</script>
