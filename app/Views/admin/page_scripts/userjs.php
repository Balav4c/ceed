<script>

    $(document).ready(function () {
            var baseUrl = "<?= base_url() ?>";
$('#saveUserBtn').click(function(e) {
    e.preventDefault();
    var url = baseUrl + "admin/save/user";

    $.post(url, $('#userForm').serialize(), function(response) {
        $('#messageBox').removeClass('d-none'); 
        if (response.status == 1) {
            $('#messageBox')
                .removeClass('alert-danger')
                .addClass('alert-success')
                .text(response.msg)
                .show();

            setTimeout(function() {
                window.location.href = baseUrl + "admin/manage_user/";
            }, 1500);
        } else {
            $('#messageBox')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .text(response.message)
                .show();
        }

        setTimeout(function() {
            $('#messageBox').fadeOut();
        }, 2000);
    }, 'json');
});


    });
let table = "";
const alertBox = $('.alert');

table = $('#userTable').DataTable({
    ajax: {
        url: "<?= base_url('admin/manage_user/userlistajax') ?>",
        type: "POST",
        dataSrc: "data"
    },
    ordering: true,  
    searching: true,
    paging: true,
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
        { data: "slno" },
        {
            data: "name",
            render: function (data) {
                if (!data || typeof data !== 'string') return '';
                return data.replace(/\b\w/g, c => c.toUpperCase());
            }
        },
        { data: "email" },
        {
            data: "role_name",  
            render: function (data) {
                return data ? data : "No Role";
            }
        },
        {
            data: "user_id",
            render: function (id) {
                return `
                    <div class="d-flex align-items-center gap-3">
                        <a href="<?= base_url('admin/adduser/edit/') ?>${id}" 
                           title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <a href="javascript:void(0);" 
                           class="delete-all" data-id="${id}" 
                           title="Delete" style="color: #dc3545;">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </div>
                `;
            }
        },
        { data: "user_id", visible: false }
    ],

    order: [[5, 'desc']], 
    columnDefs: [
        { searchable: false, orderable: false, targets: [0, 4] }
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



    
    
</script>
