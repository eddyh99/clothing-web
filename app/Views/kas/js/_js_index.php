<script nonce="<?= esc($nonce) ?>">
    $('#tabel_kas').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/kas/show_kas",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_kas() ===", response);

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
                data: 'entry_type'
            },
            {
                data: 'description'
            },
            {
                data: 'amount'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/kas/update/${full.cash_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-kas" data-id="${full.cash_id}" data-name="${full.cash_id}" data-bs-toggle="modal" data-bs-target="#modal_deletekas"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data kas tersedia."
        }
    });

    $(function() {
        // Klik tombol delete kas
        $(document).on('click', '.btn-delete-kas', function () {
            $('#kasIdToDelete').val($(this).data('id'));
            $('#kasNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#kasIdToDelete').val();

            $.getJSON(`<?= base_url('members/kas/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletekas').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_kas').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus kas");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus kas");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus kas");
                    }
                });
        });

    });
</script>
