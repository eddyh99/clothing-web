<script nonce="<?= esc($nonce) ?>">
    $('#tabel_retur').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/retur/show_retur",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_retur() ===", response);

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
                data: 'nota'
            },
            {
                data: 'customer_id'
            },
            {
                data: 'return_type'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/retur/update/${full.return_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-retur" data-id="${full.return_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deleteretur"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data retur tersedia."
        }
    });

    $(function() {
        // Klik tombol delete retur
        $(document).on('click', '.btn-delete-retur', function () {
            $('#returIdToDelete').val($(this).data('id'));
            $('#returNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#returIdToDelete').val();

            $.getJSON(`<?= base_url('members/retur/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deleteretur').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_retur').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus retur");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus retur");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus retur");
                    }
                });
        });

    });
</script>
