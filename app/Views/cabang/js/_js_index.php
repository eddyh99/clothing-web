<script nonce="<?= esc($nonce) ?>">
    $('#tabel_cabang').DataTable({
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/cabang/show_cabang",
            "type": "GET",
            // "dataSrc": function(data) {
            //     // Sembunyikan tombol tambah jika sudah sampai max_branch
                
            //     // console.log("current_branch_count:", data.current_branch_count);
            //     // console.log("max_branch:", data.max_branch);
            //     const btnTambah = document.getElementById('btnTambahCabang');  
            //     const maxBranches = ;
            //     const currentCount = data.data.length || 0;

            //     if (currentCount >= maxBranches) {
            //         btnTambah.style.display = 'none';
            //     } else {
            //         btnTambah.style.display = 'inline-block';
            //     }

            //     // Pastikan data.data adalah array, lalu filter yang is_active == 1
            //     if (Array.isArray(data.data)) {
            //         return data.data.filter(row => row.is_active == 1);
            //     }
            //     return [];
            // },
            "dataSrc": function(response) {
                console.log("=== RAW response dari show_cabang() ===", response);

                const btnTambah = document.getElementById('btnTambahCabang');  
                const maxBranches = 5; // sementara hardcode

                // Ambil array dari response.data
                const branches = Array.isArray(response.data) ? response.data : [];
                console.log("=== Parsed branches ===", branches);

                const currentCount = branches.length;
                console.log("=== currentCount ===", currentCount, "maxBranches:", maxBranches);

                if (currentCount >= maxBranches) {
                    btnTambah.style.display = 'none';
                } else {
                    btnTambah.style.display = 'inline-block';
                }

                // Map supaya branch_id jadi id
                const mapped = branches.map(row => ({
                    ...row,
                    id: row.branch_id
                }));

                console.log("=== Mapped branches (buat DataTable) ===", mapped);

                return mapped;
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
                data: 'name',
                title: 'Nama Cabang'
            },
            {
                data: 'address',
                title: 'Alamat'
            },
            {
                data: 'phone',
                title: 'Telepon'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `<a href="<?= base_url() ?>members/cabang/update/${full.id}" class="btn btn-sm btn-primary rounded-2"><i class="mdi mdi-square-edit-outline"></i></a>`;
                    const btndel = `<a href="#" class="btn btn-sm btn-danger btn-delete-branch" data-id="${full.id}" data-name="${full.name}" data-bs-toggle="modal" data-bs-target="#modal_deletebranch"><i class="mdi mdi-close-thick"></i></a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data cabang tersedia."
        }
    });

    $(function() {

        // Klik tombol delete
        $(document).on('click', '.btn-delete-branch', function () {
            $('#branchIdToDelete').val($(this).data('id'));
            $('#branchNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#branchIdToDelete').val();

            $.getJSON(`<?= base_url('members/cabang/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deletebranch').modal('hide');
                        success_alert(data.message, 'Cabang');
                        $('#tabel_cabang').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus cabang");
                    }
                })
                .fail(function () {
                    failed_alert(null, "Terjadi kesalahan saat menghapus cabang");
                });
        });

    });

</script>