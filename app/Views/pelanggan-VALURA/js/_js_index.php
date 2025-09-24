<script nonce="<?= esc($nonce) ?>">
    $('#btnExportExcel').on('click', function () {
        tblplg.button(1).trigger();
    });
    $('#btnExportPdf').on('click', function () {
        tblplg.button(0).trigger();
    });
    $('#btnExportPrint').on('click', function () {
        tblplg.button(2).trigger();
    });

    let tblplg = $('#tabel_pelanggan').DataTable({       
        buttons: [
            {
                extend: 'pdfHtml5',
                text: '<i class="mdi mdi-file-pdf-outline"></i> PDF',
                className: 'btn btn-sm btn-danger me-2',
                title: 'Daftar Pelanggan',
                exportOptions: { columns: [0,1,2,3,4,5] }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="mdi mdi-file-excel-outline"></i> Excel',
                className: 'btn btn-sm btn-success me-2',
                title: 'Daftar Pelanggan',
                exportOptions: { columns: [0,1,2,3,4,5] }
            },
            {
                extend: 'print',
                text: '<i class="mdi mdi-printer"></i> Print',
                className: 'btn btn-sm btn-secondary',
                title: 'Daftar Pelanggan',
                exportOptions: { columns: [0,1,2,3,4,5] }
            }
        ],
        "responsive": true,
        "order": [],
        "ajax": {
            "url": "<?= base_url() ?>members/pelanggan/show_pelanggan",
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
                data: 'id_type'
            },
            {
                data: 'id_number'
            },
            {
                data: 'country'
            },
            {
                data: 'phone'
            },
            {
                data: null,
                render: function(data, type, full, meta) {
                    const btnedit = `
                    <a 
                        href="<?= base_url() ?>members/pelanggan/update/${full.id}" 
                        class="btn btn-sm btn-primary rounded-2">
                            <i class="mdi mdi-square-edit-outline"></i>
                    </a>`;
                    const btndel = `
                    <a 
                        href="#" 
                        class="btn btn-sm btn-danger btn-delete-client" 
                        data-id="${full.id}" 
                        data-name="${full.name}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modal_deleteclient">
                            <i class="mdi mdi-close-thick"></i>
                    </a>`;
                    return btnedit + ' ' + btndel;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "language": {
            "emptyTable": "Tidak ada data pelanggan tersedia."
        }
    });

    $(function() {

        // Klik tombol delete client
        $(document).on('click', '.btn-delete-client', function () {
            $('#clientIdToDelete').val($(this).data('id'));
            $('#clientNameToDelete').text($(this).data('name'));
        });

        // Konfirmasi delete client
        $('#confirmDeleteBtn').on('click', function () {
            const id = $('#clientIdToDelete').val();

            $.getJSON(`<?= base_url('members/pelanggan/delete') ?>`, { id: id })
                .done(function (data) {
                    if (data.success) {
                        $('#modal_deleteclient').modal('hide');
                        success_alert(data.message, 'Pelanggan');
                        $('#tabel_pelanggan').DataTable().ajax.reload(null, false);
                    } else {
                        failed_alert(data.message, "Gagal menghapus pelanggan");
                    }
                })
                .fail(function (xhr) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            failed_alert(null, "Terjadi kesalahan saat menghapus pelanggan");
                        }
                    } catch (e) {
                        failed_alert(null, "Terjadi kesalahan saat menghapus pelanggan");
                    }
                });
                // .fail(function () {
                //     failed_alert(null, "Terjadi kesalahan saat menghapus pelanggan");
                // });
        });

    });

</script>