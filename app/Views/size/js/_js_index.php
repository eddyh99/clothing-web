<script nonce="<?= esc($nonce) ?>">
    $('#tabel_size').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/size/show_size",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_size() ===", response);

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
                data: 'size'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/size/update/${full.size_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-size" data-id="${full.size_id}" data-size="${full.size}" data-bs-toggle="modal" data-bs-target="#modal_deletesize"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data size tersedia."
        }
    });

    $(function() {
        // Klik tombol delete size
        $(document).on('click', '.btn-delete-size', function () {
            $('#sizeIdToDelete').val($(this).data('id'));
            $('#sizeNameToDelete').text($(this).data('size'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#sizeIdToDelete').val();

            $.getJSON(`<?= base_url('members/size/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletesize').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_size').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus size");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus size");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus size");
                    }
                });
        });

    });
</script>
