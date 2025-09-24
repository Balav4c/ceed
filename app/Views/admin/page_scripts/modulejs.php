<script>
    // fileupload
    (function ($) {
        var fileUploadCount = 0;

        $.fn.fileUpload = function () {
            return this.each(function () {
                var fileUploadDiv = $(this);
                var fileUploadId = `fileUpload-${++fileUploadCount}`;

                var fileDivContent = `
                    <label for="${fileUploadId}" class="file-upload">
                        <div style="margin-top: 42px;">
                            <i class="material-icons-outlined">video_upload</i>
                            <p>Drag & Drop Files Here</p>
                            <span>OR</span>
                            <div>Browse Files</div>
                        </div>
                        <!-- FIX: set correct name -->
                       <input type="file" id="${fileUploadId}" name="module_videos[]" multiple hidden />
                    </label>
                `;

                fileUploadDiv.html(fileDivContent).addClass("file-container");

                var table = null;
                var tableBody = null;
                function createTable() {
                    table = $(`
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 30%;">File Name</th>
                                <th>Preview</th>
                                <th style="width: 20%;">Size</th>
                                <th>Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                `);

                    tableBody = table.find("tbody");
                    fileUploadDiv.append(table);
                }
                function uploadFiles(files) {
                    let formData = new FormData();

                    // module_id is optional now, you can remove or set dynamically
                    formData.append('module_id', $('#module_id').val() || '');

                    for (let i = 0; i < files.length; i++) {
                        formData.append('module_videos[]', files[i]);
                    }

                    $.ajax({
                        url: "<?= base_url('admin/coursemodule/uploadVideo') ?>",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            let $msg = $('#messageBox');
                            $msg.removeClass('d-none alert-success alert-danger');

                            if (response.status === 'success') {
                                $msg.addClass('alert-success')
                                    .text(response.message || 'Videos uploaded successfully!')
                                    .show();

                                // Store uploaded filenames in hidden input for save later
                                let existing = $('#uploaded_videos').val();
                                let newVideos = response.uploaded.join(',');
                                $('#uploaded_videos').val(existing ? existing + ',' + newVideos : newVideos);

                                setTimeout(function () {
                                    $msg.fadeOut();
                                    if (typeof table !== "undefined" && table.ajax) {
                                        table.ajax.reload(null, false);
                                    }
                                }, 1500);

                            } else {
                                $msg.addClass('alert-danger')
                                    .text(response.message || (response.errors ? response.errors.join(', ') : 'Upload failed'))
                                    .show();

                                setTimeout(function () { $msg.fadeOut(); }, 2000);
                            }
                        },
                        error: function (xhr, status, error) {
                            let $msg = $('#messageBox');
                            $msg.removeClass('d-none alert-success')
                                .addClass('alert-danger')
                                .text('Error uploading video(s)')
                                .show();

                            setTimeout(function () { $msg.fadeOut(); }, 2000);
                        }
                    });
                }


                function handleFiles(files) {
                    if (!table) {
                        createTable();
                    }
                    tableBody.empty();

                    if (files.length > 0) {
                        $.each(files, function (index, file) {
                            var fileName = file.name;
                            var fileSize = (file.size / 1024).toFixed(2) + " KB";
                            var fileType = file.type;
                            var preview = fileType.startsWith("image")
                                ? `<img src="${URL.createObjectURL(file)}" alt="${fileName}" height="30">`
                                : `<i class="material-icons-outlined">visibility_off</i>`;

                            tableBody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${fileName}</td>
                                    <td>${preview}</td>
                                    <td>${fileSize}</td>
                                    <td>${fileType}</td>
                                    <td><button type="button" class="deleteBtn"><i class="bi bi-trash-fill"></i></button></td>
                                </tr>
                            `);
                        });
                        tableBody.find(".deleteBtn").click(function () {
                            $(this).closest("tr").remove();

                            if (tableBody.find("tr").length === 0) {
                                tableBody.append('<tr><td colspan="6" class="no-file">No files selected!</td></tr>');
                            }
                        });
                        uploadFiles(files);
                    }
                }

                fileUploadDiv.on({
                    dragover: function (e) {
                        e.preventDefault();
                        fileUploadDiv.toggleClass("dragover", e.type === "dragover");
                    },
                    drop: function (e) {
                        e.preventDefault();
                        fileUploadDiv.removeClass("dragover");

                        let files = e.originalEvent.dataTransfer.files;
                        handleFiles(files);

                        let dt = new DataTransfer();
                        for (let i = 0; i < files.length; i++) {
                            dt.items.add(files[i]);
                        }
                        fileUploadDiv.find(`#${fileUploadId}`)[0].files = dt.files;
                    },

                });
                fileUploadDiv.find(`#${fileUploadId}`).change(function () {
                    handleFiles(this.files);
                });
            });
        };
    })(jQuery);


    $(document).ready(function () {

        $('.content').richText();
        $("#fileUpload").fileUpload();

        const $saveBtn = $('#saveBtn');
        const $moduleForm = $('#moduleForm');
        const $moduleContainer = $('#module-container');
        const $messageBox = $('#messageBox');
        $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });

        function enableSaveButton() {
            $saveBtn.prop('disabled', false).css({ opacity: 1, pointerEvents: 'auto' });
        }

        $moduleForm.on('input change', 'input, textarea', enableSaveButton);
        $('#addModule').click(function () {
            let moduleItem = $('.module-item:first').clone();
            moduleItem.find('input, textarea').val('');
            moduleItem.find('#fileUpload').fileUpload();
            $moduleContainer.append(moduleItem);
        });
        $(document).on('click', '.remove-module', function () {
            if ($('.module-item').length > 1) {
                $(this).closest('.module-item').remove();
            } else {
                alert('At least one module is required.');
            }
        });
        $('#moduleTable').on('click', '.read-desc', function () {
            var fullDescription = $(this).data('description');
            $('#modalDescription').text(fullDescription);
            var myModal = new bootstrap.Modal(document.getElementById('descriptionModal'));
            myModal.show();
        });
        $(document).ready(function () {
            const videoModalEl = document.getElementById('videoModal');
            const videoModal = new bootstrap.Modal(videoModalEl);

            $(document).on("click", ".play-video-link", function () {
                let videoFile = $(this).data("video");
                let title = $(this).data("title");

                $("#videoTitle").text(title);

                let videoPlayer = $("#videoPlayer")[0];
                let source = $("#videoPlayer source")[0];

                source.src = videoFile;
                source.type = "video/mp4";

                videoPlayer.load();
                videoPlayer.play();

                videoModal.show();
            });

            $(videoModalEl).on("hidden.bs.modal", function () {
                let videoPlayer = $("#videoPlayer")[0];
                $("#videoPlayer source").attr("src", "");
                videoPlayer.load();
            });
        });



        $('#moduleForm').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            let isValid = true;

            form.find('input[name="module_videos[]"]').each(function () {
                let files = this.files;
                if (files.length > 0) {
                    $.each(files, function (i, file) {
                        let fileType = file.type;
                        if (!['video/mp4', 'video/webm', 'video/ogg'].includes(fileType)) {
                            isValid = false;
                        }
                    });
                }
            });

            if (!isValid) {
                $('#messageBox')
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .html("Please Upload Video Files Only.")
                    .fadeIn()
                    .delay(2000)
                    .fadeOut(500);

                return;
            }

            $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });

            $.post(url, form.serialize(), function (response) {
                var $messageBox = $('#messageBox');
                $messageBox.removeClass('d-none alert-success alert-danger');

                if (response.status === 'success' || response.status == 1) {
                    $messageBox
                        .addClass('alert-success')
                        .text(response.msg || response.message)
                        .show();

                    setTimeout(function () {
                        window.location.href = "<?= base_url('admin/manage_module'); ?>";
                    }, 1500);

                } else {
                    $messageBox
                        .addClass('alert-danger')
                        .text(response.message || 'Something went wrong')
                        .fadeIn()
                        .delay(2000)
                        .fadeOut(500);
                }
            }, 'json')
                .fail(function () {
                    $('#messageBox')
                        .removeClass('d-none alert-success')
                        .addClass('alert-danger')
                        .text('Error submitting the form. Please try again.')
                        .show();
                });
        });

        $(document).ready(function () {
            let table = "";
            const alertBox = $('.alert');

            table = $('#moduleTable').DataTable({
                ajax: {
                    url: "<?= base_url('admin/manage_module/modulelistajax') ?>",
                    type: "POST",
                    dataSrc: "data"
                },
                serverSide: true,
                processing: true,
                ordering: true,
                searching: true,
                paging: true,
                dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 text-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3 d-flex align-items-center'<'col-sm-5'i><'col-sm-7 text-end'p>>",
                drawCallback: function () {
                    $('.dataTables_info').text(function (_, txt) {
                        return txt.replace(/\(filtered.*\)/, '').trim();
                    });
                },
                columns: [
                    { data: "slno", className: "text-start" },
                    {
                        data: "module_name",
                        render: function (data) {
                            return (data && typeof data === 'string')
                                ? data.replace(/\b\w/g, c => c.toUpperCase())
                                : '';
                        }
                    },
                    {
                        data: "description",
                        render: function (data) {
                            if (!data) return '<a href="javascript:void(0)" class="read-desc" data-description="">Read Description</a>';
                            let safeData = data.replace(/"/g, '&quot;');
                            return `<a href="javascript:void(0)" class="read-desc" data-description="${safeData}">Read Description</a>`;
                        }
                    },
                    { data: "duration_weeks" },
                    {
                        data: "module_videos",
                        render: function (data, type, row) {
                            if (!data || data.trim() === "")
                                return '<span class="text-muted">No Videos</span>';

                            let videos = data.split(',');
                            return videos.map(v => {
                                v = v.trim();
                                let videoUrl = "<?= base_url('public/uploads/videos/') ?>" + v;
                                return `<a href="javascript:void(0);" class="play-video-link text-primary" data-video="${videoUrl}" data-title="${v}">Play Video
                                            <i class="bi bi-play-circle"></i> 
                                        </a>`;
                            }).join('<br>');
                        }
                    },

                    {
                        data: "status",
                        render: function (data, type, row) {
                            let checked = data == 1 ? 'checked' : '';
                            return `
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-status" type="checkbox" 
                                data-id="${row.module_id}" ${checked}>
                            
                        </div>
                    `;
                        }
                    },
                    {
                        data: "module_id",
                        render: function (id) {
                            return `<div class="d-flex align-items-center gap-3">
                        <a href="<?= base_url('admin/manage_module/edit/') ?>${id}" title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <a href="javascript:void(0);" class="delete-module" data-id="${id}" title="Delete" style="color: #dc3545;">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </div>`;
                        }
                    }
                ],
                order: [[7, 'desc']],
                columnDefs: [
                    { searchable: false, orderable: false, targets: [0, 5, 6] }
                ],
                language: { infoFiltered: "" },
                scrollX: false,
                autoWidth: false
            });

            table.on('order.dt search.dt draw.dt', function () {
                table.column(0, { search: 'applied', order: 'applied' })
                    .nodes()
                    .each(function (cell, i) {
                        var pageInfo = table.page.info();
                        cell.innerHTML = pageInfo.start + i + 1;
                    });
            });
        });
    });
    // toggle status 
    $('#moduleTable').on('change', '.toggle-status', function () {
        let moduleId = $(this).data('id');
        let newStatus = $(this).is(':checked') ? 1 : 2;

        $.ajax({
            url: "<?= base_url('admin/manage_module/toggleStatus') ?>",
            type: "POST",
            data: {
                module_id: moduleId,
                status: newStatus,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: "json",
            success: function (response) {
                let $msg = $('#messageBox');
                $msg.removeClass('d-none alert-success alert-danger');

                if (response.status === 'success') {
                    $msg.addClass('alert-success').text(response.message).show();
                    setTimeout(function () {
                        $msg.fadeOut();
                        table.ajax.reload(null, false);
                    }, 1500);
                } else {
                    $msg.addClass('alert-danger').text(response.message || 'Failed to update status').show();
                    setTimeout(function () {
                        $msg.fadeOut();
                    }, 2000);
                }
            },
            error: function (xhr, status, error) {
                let $msg = $('#messageBox');
                $msg.removeClass('d-none alert-success').addClass('alert-danger')
                    .text('Error updating status').show();
                setTimeout(function () { $msg.fadeOut(); }, 2000);
            }
        });
    });
    // delete pop up 


    $(document).on("click", ".delete-module", function (e) {
        e.preventDefault();
        let roleId = $(this).data("id");

        swal({
            title: "Are You Sure?",
            text: "You Want To Delete This Module!",
            icon: "warning",
            buttons: {
                cancel: {
                    visible: true,
                    text: "Cancel",
                    className: "btn btn-danger",
                },
                confirm: {
                    text: "Delete",
                    className: "btn btn-success",
                },
            },
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "<?= base_url('admin/manage_module/delete'); ?>",
                    type: "POST",
                    data: { id: roleId },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            swal("Deleted!", response.message, {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-success",
                                    },
                                },
                            });
                            $('#moduleTable').DataTable().ajax.reload(null, false);
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function () {
                        swal("Error!", "Something went wrong. Try again.", "error");
                    },
                });
            } else {
                swal("Your Module Is Safe!", {
                    buttons: {
                        confirm: {
                            className: "btn btn-success",
                        },
                    },
                });
            }
        });
    });

    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-1VDDWMRSTH');
    try {
        fetch(new Request("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js", { method: 'HEAD', mode: 'no-cors' })).then(function (response) {
            return true;
        }).catch(function (e) {
            var carbonScript = document.createElement("script");
            carbonScript.src = "//cdn.carbonads.com/carbon.js?serve=CK7DKKQU&placement=wwwjqueryscriptnet";
            carbonScript.id = "_carbonads_js";
            document.getElementById("carbon-block").appendChild(carbonScript);
        });
    } catch (error) {
        console.log(error);
    }
</script>