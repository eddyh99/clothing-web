<script nonce="<?= esc($nonce) ?>">
    $('#tabel_membership').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/membership/show_membership",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_membership() ===", response);

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
                data: 'point_multiplier'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/membership/update/${full.membership_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-membership" data-id="${full.membership_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletemembership"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data membership tersedia."
        }
    });

    $(function() {
        // Klik tombol delete membership
        $(document).on('click', '.btn-delete-membership', function () {
            $('#membershipIdToDelete').val($(this).data('id'));
            $('#membershipNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#membershipIdToDelete').val();

            $.getJSON(`<?= base_url('members/membership/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletemembership').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_membership').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus membership");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus membership");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus membership");
                    }
                });
        });

    });
</script>
