<script nonce="<?= esc($nonce) ?>">
    let kasTable;

    $("#btnLihat").on("click",function(){
        console.log('Filter cabang:', $('#filter_cabang').val());
        kasTable.ajax.reload();
    })

    kasTable = $('#tabel_kas').DataTable({
        "dom": '<"d-flex justify-content-between align-items-center flex-wrap"lf>t<"d-flex justify-content-between align-items-center"ip>',
        "responsive": true,
        "ajax": {
            "url": "<?= base_url() ?>members/kas/show_kas",
            "type": "GET",
            "data": function(d) {
                // Kirim parameter filter cabang ke server
                const branchValue = $('#filter_cabang').val();
                console.log('Sending branch filter:', branchValue);
                d.branch = branchValue;
            },
            "dataSrc": function(data) {
                console.log('Raw data received:', data);
                return data;
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
                "data": "occurred_at",
                "render": function(data) {
                    return formatDate(data);
                }
            },
            { 
                "data": "movement_type",
                "render": function(data) {
                    switch(data) {
                        case 'IN': return 'Kas Masuk';
                        case 'OUT': return 'Kas Keluar';
                        case 'AWAL': return 'Kas Awal';
                        default: return data;
                    }
                }
            },
            { 
                "data": "reason"                
            },
            { 
                "data": "amount",
                "render": function(data, type, row) {
                    return formatCurrency(data, row.currency_code);
                },
                "className": "text-end"
            },
            {
                "data": "id",
                "render": function(data, type, row) {
                    const canEdit   = <?= can('exchangerate', 'canUpdate') ? 'true' : 'false' ?>;
                    const canDelete = <?= can('exchangerate', 'canDelete') ? 'true' : 'false' ?>;

                    // if no permission at all → return empty
                    if (!canEdit && !canDelete) return '';

                    let btn = '';

                    if (canEdit) {
                        btn += `<a href="<?= base_url() ?>members/kas/update/${data}" 
                                    class="btn btn-sm btn-primary rounded-2 btn-action">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                </a> `;
                    }

                    if (canDelete) {
                        btn += `<a href="#" 
                                    class="btn btn-sm btn-danger btn-delete-kas btn-action" 
                                    data-id="${data}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modal_deletekas">
                                    <i class="mdi mdi-close-thick"></i>
                                </a>`;
                    }

                    return btn;
                },
                orderable: false,
                searchable: false,
                width: "100px"
            }
        ],
        "order": [[1, "desc"]],
        "initComplete": function() {
            console.log('Tabel berhasil diinisialisasi');
        }
    });

    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = {
            day: '2-digit',
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('id-ID', options);
    }

    function formatCurrency(amount, currencyId) {
        const num = parseFloat(amount) || 0;
        let formatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: currencyId,
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(num);

        return formatted;
    }

    $(document).on('click', '.btn-delete-kas', function(e) {
        e.preventDefault();
        const kasId = $(this).data('id');
        $('#kasIdToDelete').val(kasId);
    });

    // Reset tombol ketika modal ditutup
    $('#modal_deletekas').on('hidden.bs.modal', function() {
        $('#confirmDeleteBtn').prop('disabled', false).html('Ya');
    });

    // Submit form delete kas
    $('#frmdeletedkas').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const id = $('#kasIdToDelete').val();
        const reason = $('#deleted_reason').val();

        $('#confirmDeleteBtn').prop('disabled', true).html('Menghapus...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#modal_deletekas').modal('hide');
                    success_alert(data.message, 'Kas');
                    $('#tabel_kas').DataTable().ajax.reload(null, false);
                } else {
                    failed_alert(data.message, "Gagal menghapus kas");
                }
            },
            error: function(xhr, error, thrown) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        failed_alert(null, "Terjadi kesalahan saat menghapus kas");
                    }
                } catch (e) {
                    failed_alert(null, "Terjadi kesalahan saat menghapus kas");
                }
            },
            complete: function() {
                $('#confirmDeleteBtn').prop('disabled', false).html('Ya');
            }
        });
    });
</script>
