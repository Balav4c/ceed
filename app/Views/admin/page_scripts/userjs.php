<script>

    $(document).ready(function () {
            var baseUrl = "<?= base_url() ?>";
$('#saveUserBtn').click(function(e) {
    e.preventDefault();
    var url = baseUrl + "admin/save/user";
 
    $.post(url, $('#userForm').serialize(), function(response) {
        if (response.status == 1) {
            $('#messageBox')
                .removeClass('alert-danger')
                .addClass('alert-success')
                .text(response.msg || 'Category Created Successfully!')
                .show();
 
            setTimeout(function() {
                window.location.href = baseUrl + "admin/manage_user/";
            }, 1500);
        } else {
            $('#messageBox')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .text(response.message || 'Please Fill all the Data')
                .show();
        }
 
        setTimeout(function() {
            $('#messageBox').empty().hide();
        }, 2000);
    }, 'json');
});

    });
    // $(document).ready(function () {
    //     const alertBox = $(".alert"); 
    // $('#user_form').submit(function (e) {
    //     e.preventDefault();
    //     // let name = $('#user_form [name="name"]').val().trim();
    //     // let email = $('#user_form [name="email"]').val().trim();
    //     // let password = $('#user_form [name="password"]').val().trim();
    //     // // let role_id = $('#user_form [name="role_id"]').val();

    //     // if (!name || !email || !password ) {
    //     //     alertBox.removeClass().addClass('alert alert-warning text-center position-fixed')
    //     //         .text('All fields are required').fadeIn();
    //     //     setTimeout(() => alertBox.fadeOut(), 3000);
    //     //     return;
    //     // }

    //     const formData = $(this).serialize();

    //     $.ajax({
    //           url: "<?= base_url('admin/save') ?>", 
    //         type: "POST",
    //         data: formData,
    //         dataType: "json",
    //         success: function (res) {
    //             if (res.status === 'success') {
    //                 alertBox.removeClass().addClass('alert alert-success text-center position-fixed')
    //                     .text('User Added Successfully').fadeIn();
    //                 setTimeout(() => alertBox.fadeOut(), 2000);
    //                 $('#user_form')[0].reset();
    //                 table.ajax.reload(null, false); // reload DataTable
    //             } else {
    //                 alertBox.removeClass().addClass('alert alert-warning text-center position-fixed')
    //                     .text(res.message || 'Save Failed').fadeIn();
    //                 setTimeout(() => alertBox.fadeOut(), 3000);
    //             }
    //         },
    //         error: function () {
    //             alertBox.removeClass().addClass('alert alert-danger text-center position-fixed')
    //                 .text('Error Occurred While Saving User').fadeIn();
    //             setTimeout(() => alertBox.fadeOut(), 3000);
    //         }
    //     });

    //     // $.ajax({
    //     //     url: url,
    //     //     type: 'POST',
    //     //     data: form.serialize(),
    //     //     dataType: 'json',
    //     //     success: function(res) {
    //     //         if (res.status === 'success') {
    //     //             alert('Role saved successfully!');
    //     //             window.location.href = "<?= base_url('admin/manage_user/createUser') ?>";
    //     //         } else {
    //     //             alert('Error: ' + res.message);
    //     //         }
    //     //     },
    //     //     error: function(xhr, status, error) {
    //     //         console.log(xhr.responseText);
    //     //         alert('Something went wrong! Check console for details.');
    //     //     }
    //     // });
    // });
    // });
</script>
