<script nonce="<?= esc($nonce) ?>">
    $('#tabel_kategori').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/kategori/show_kategori",
            "type": "GET",
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_kategori() ===", response);

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
                    const btnedit = `<a href="<?= base_url() ?>members/kategori/update/${full.category_id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-kategori" data-id="${full.category_id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletekategori"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data kategori tersedia."
        }
    });

    $(function() {
        // Klik tombol delete kategori
        $(document).on('click', '.btn-delete-kategori', function () {
            $('#kategoriIdToDelete').val($(this).data('id'));
            $('#kategoriNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#kategoriIdToDelete').val();

            $.getJSON(`<?= base_url('members/kategori/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletekategori').modal('hide');
                        success_alert(data.message, 'Brand');
                        $('#tabel_kategori').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus kategori");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus kategori");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus kategori");
                    }
                });
        });

    });
</script>
