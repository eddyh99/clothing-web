<script nonce="<?= esc($nonce) ?>">
    $('#tabel_promosi').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/promosi/show_promosi",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_promosi() ===", response);

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
                data: 'promo_type'
            },
            {
                data: 'start_date'
            },
            {
                data: 'end_date'
            },
            {
                data: 'requires_member'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/promosi/update/${full.promo_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-promosi" data-id="${full.promo_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletepromosi"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data promosi tersedia."
        }
    });

    $(function() {
        // Klik tombol delete promosi
        $(document).on('click', '.btn-delete-promosi', function () {
            $('#promosiIdToDelete').val($(this).data('id'));
            $('#promosiNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#promosiIdToDelete').val();

            $.getJSON(`<?= base_url('members/promosi/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletepromosi').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_promosi').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus promosi");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus promosi");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus promosi");
                    }
                });
        });

    });
</script>
