<script>
$(document).ready(function() {
    $('#select_all_permissions').on('change', function() {
        $('.permission-checkbox').prop('checked', $(this).prop('checked'));
    });

    $('.permission-checkbox').on('change', function() {
        if ($('.permission-checkbox:checked').length === $('.permission-checkbox').length) {
            $('#select_all_permissions').prop('checked', true);
        } else {
            $('#select_all_permissions').prop('checked', false);
        }
    });

    function formatDateToDMY(input) {
    const date = new Date(input);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); 
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}
    let table="";
        const alertBox = $('.alert');
        table = $('#roleTable').DataTable({
            ajax: {
                url: "<?= base_url('admin/manage_role/rolelistajax') ?>",
                type: "POST",
                dataSrc: "data"
            },
			sort:true,
			searching:true,
			paging:true,
            processing: true,
            serverSide: true,
            dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",

                drawCallback: function () {
                    let info = $('.dataTables_info');
                    info.text(info.text().replace(/\(filtered.*\)/, '').trim());
                },
            columns: [
                {
                    data: "slno",
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: "role_name",
                    render: function (data, type, row) {
                        if (!data || typeof data !== 'string') return '';
                        return data.replace(/\b\w/g, c => c.toUpperCase());
                    }
                },

                {
                    data: "role_id",
                    render: function (id, type, row, meta) {
                        const permissions = row.permissions || [];
                        if (permissions.length > 0) {
                            return '<ul class="mb-0">' + permissions.map(p => `<li>${p}</li>`).join('') + '</ul>';
                        }
                        return '<em>No Permissions Assigned</em>';
                    }
                },
                {
                    data: "created_at",
                    render: function (data) {
                        const d = new Date(data);
                        return isNaN(d) ? '-' : formatDateToDMY(d);
                    }
                },
                {
                    data: "updated_at",
                    render: function (data) {
                        const d = new Date(data);
                        return isNaN(d) ? '-' : formatDateToDMY(d);
                    }
                },
                {
                    data: "role_id",
                    render: function (id) {
                        return `
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= base_url('admin/manage_role/edit/') ?>${id}" title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="javascript:void(0);" class="delete-all" data-id="${id}" title="Delete" style="color: #dc3545;">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    `;
                    }
                },
                { data: "role_id", visible: false }
            ],
            
            order: [[6, 'desc']],
            columnDefs: [
                { searchable: false, orderable: false, targets: [0, 2, 5] } 
            ],
            language: {
        infoFiltered: "", 
    }
        });


        table.on('order.dt search.dt draw.dt', function () {
            table.column(0, { search: 'applied', order: 'applied' })
                .nodes()
                .each(function (cell, i) {
                    var pageInfo = table.page.info();
                    cell.innerHTML = pageInfo.start + i + 1;
                });
        });


    $('#roleForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

       $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    alert('Role saved successfully!');
                    window.location.href = "<?= base_url('admin/manage_role') ?>";
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('Something went wrong! Check console for details.');
            }
        });
    });
});
</script>
