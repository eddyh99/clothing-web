<script nonce="<?= esc($nonce) ?>">
    $('#tabel_partnerkonsi').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/partnerkonsi/show_partnerkonsi",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_partnerkonsi() ===", response);

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
                data: 'phone'
            },
            {
                data: 'address'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/partnerkonsi/update/${full.partner_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-partnerkonsi" data-id="${full.partner_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletepartnerkonsi"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data partnerkonsi tersedia."
        }
    });

    $(function() {
        // Klik tombol delete partnerkonsi
        $(document).on('click', '.btn-delete-partnerkonsi', function () {
            $('#partnerkonsiIdToDelete').val($(this).data('id'));
            $('#partnerkonsiNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#partnerkonsiIdToDelete').val();

            $.getJSON(`<?= base_url('members/partnerkonsi/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletepartnerkonsi').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_partnerkonsi').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus partnerkonsi");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus partnerkonsi");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus partnerkonsi");
                    }
                });
        });

    });
</script>
