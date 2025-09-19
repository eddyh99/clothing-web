<script nonce="<?= esc($nonce) ?>">
    // 1. Menangani DataTable
    $('#tabel_role').DataTable({
        "dom": '<"d-flex justify-content-between align-items-center flex-wrap"lf>t<"d-flex justify-content-between align-items-center"ip>',
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/role/show_role",
            "type": "GET",
            "dataSrc": function(data) {
                // Pastikan data.data adalah array, lalu filter yang is_active == 1
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
                data: 'name'
            },
            {
                data: 'permissions',
                render: function(data, type, full, meta) {
                    if (full.name=='admin'){
                        return '==== full akses ===='
                    }else{
                        try {
                            const parsed = JSON.parse(data);
                            const menuNames = Object.keys(parsed);
                            return menuNames.join(', ');
                        } catch (e) {
                            return '-';
                        }
                    }
                }
            },
            {
                data: null,
                render: function(data, type, full, meta) { 
                    let btnedit = '', btndel = '';
                    if (full.name != 'admin') {
                        btnedit = `<a href="<?= base_url() ?>members/role/update/${full.id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                        btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-role" data-id="${full.id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deleterole"><i class="mdi mdi-close-thick"></i></a>`;
                    }                   
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }

        ],
        "language": {
            "emptyTable": "Tidak ada data role tersedia."
        }
    });

    // 2. Menangani Delete Modal
    $(function() {

        // Klik tombol delete role
        $(document).on('click', '.btn-delete-role', function () {
            $('#roleIdToDelete').val($(this).data('id'));
            $('#roleNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#roleIdToDelete').val();

            $.getJSON(`<?= base_url('members/role/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deleterole').modal('hide');
                        success_alert(data.message, 'Role');
                        $('#tabel_role').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus role");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus role");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus role");
                    }
                });
        });

    });

    // 3. Menangani Check Box
    document.addEventListener('DOMContentLoaded', function () {
        const menuCheckboxes = document.querySelectorAll('.menu-checkbox');

        menuCheckboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', function () {
                const permissionGroup = document.getElementById('permissions_' + index);
                if (this.checked) {
                    permissionGroup.style.display = 'block';
                } else {
                    permissionGroup.style.display = 'none';
                    // Uncheck all child permissions if menu is unchecked
                    permissionGroup.querySelectorAll('input[type="checkbox"]').forEach(child => child.checked = false);
                }
            });
        });
    });
</script>