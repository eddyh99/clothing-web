<script nonce="<?= esc($nonce) ?>">
$('#tabel_pengguna').DataTable({
    "responsive": true,
    "order": [],
    "ajax": {
        "url": "<?= base_url() ?>members/pengguna/show_pengguna",
        "type": "GET",
        "dataSrc": function(data) {
            if (Array.isArray(data.data)) {
                return data.data.filter(row => row.is_active == 1);
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
            data: 'username'
        },
        {
            data: 'email'
        },
        {
            data: 'role_id' // ← sementara tampilkan role_id dulu
        },
        {
            data: null,
            render: function(data, type, full, meta) {
                console.log('Render row data:', full); // 🔍 cek isi row

                let btnedit = '', btndel = '';
                if (full.role_id != '1') { // contoh: anggap role_id 1 = admin
                    const updateUrl = `<?= base_url('members/pengguna/update/') ?>${full.user_id}`;
                    btnedit = `<a href="${updateUrl}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-user" data-id="${full.user_id}" data-name="${full.username}" data-bs-toggle="modal" data-bs-target="#modal_deleteuser"><i class="mdi mdi-close-thick"></i></a>`;
                }

                return btnedit + ' ' + btndel;
            },
            orderable: false,
            searchable: false,
            width: "100px"
        }
    ],
    "language": {
        "emptyTable": "Tidak ada data pengguna tersedia."
    }
});

    $(function() {

        // Klik tombol delete user
        $(document).on('click', '.btn-delete-user', function () {
            $('#userIdToDelete').val($(this).data('id'));
            $('#userNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete user
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#userIdToDelete').val();

            $.getJSON(`<?= base_url('members/pengguna/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deleteuser').modal('hide');
                        success_alert(data.message, 'Pengguna');
                        $('#tabel_pengguna').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus pengguna");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus pengguna");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus pengguna");
                    }
                });
                // .fail(function () {
                //     failed_alert(null, "Terjadi kesalahan saat menghapus pengguna");
                // });
        });

    });

</script>