<script nonce="<?= esc($nonce) ?>">
    $('#tabel_brand').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/brand/show_brand",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_brand() ===", response);

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
                data: 'description'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/brand/update/${full.brand_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-brand" data-id="${full.brand_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletebrand"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data brand tersedia."
        }
    });

    $(function() {
        // Klik tombol delete brand
        $(document).on('click', '.btn-delete-brand', function () {
            $('#brandIdToDelete').val($(this).data('id'));
            $('#brandNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#brandIdToDelete').val();

            $.getJSON(`<?= base_url('members/brand/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletebrand').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_brand').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus brand");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus brand");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus brand");
                    }
                });
        });

    });
</script>
