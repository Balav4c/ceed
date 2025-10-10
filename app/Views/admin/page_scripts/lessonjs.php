<script>
    (function ($) {
        var fileUploadCount = 0;
        $.fn.fileUpload = function () {
            return this.each(function () {
                var fileUploadDiv = $(this);
                var fileUploadId = `fileUpload-${++fileUploadCount}`;

                var fileDivContent = `
                <label for="${fileUploadId}" class="file-upload ">
                    <div style="margin-top: 15px; margin-left: 2px; margin-right: 2px;">
                        <i class="material-icons-outlined">Upload Video</i>
                        <p>Drag & Drop Files Here</p>
                        <span>OR</span>
                        <div>Select a Video</div>
                        
                        <div class="progress mt-2 p-0" style="display:none; width:100%;">
                            <div class="progress-bar" role="progressbar" style="width:0%; color: white;">0%</div>
                        </div>
                    </div>
                    
                    <input type="file" id="${fileUploadId}" name="module_videos[]" multiple accept="video/*" hidden />
                </label>
                <div id="videoPreview" class="video-preview mt-2"></div>
                `;

                fileUploadDiv.html(fileDivContent).addClass("file-container");

                var table = null;
                var tableBody = null;
                var deletedVideos = [];
                var allUploadedVideos = [];
                var newUploadedVideos = [];

                function createTable() {
                    table = $(` 
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 150px;">SI NO</th>
                                <th style="width: 180px;">Lesson Name <span class="text-danger">*</span></th>
                                <th style="text-align: center;">Preview</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                `);
                    tableBody = table.find("tbody");
                    fileUploadDiv.append(table);
                }
                function bindDeleteButtons() {
                    tableBody.find(".deleteBtn").off("click").on("click", function () {
                        let row = $(this).closest("tr");
                        let existingVideo = row.data("existing-video");

                        swal({
                            title: "Are You Sure?",
                            text: "You want to delete this video!",
                            icon: "warning",
                            buttons: {
                                cancel: { visible: true, text: "Cancel", className: "btn btn-danger" },
                                confirm: { text: "Delete", className: "btn btn-success" },
                            },
                        }).then((willDelete) => {
                            if (willDelete) {
                                if (existingVideo) {
                                    $.ajax({
                                        url: "<?= base_url('admin/coursemodule/deleteVideo') ?>",
                                        type: "POST",
                                        data: { video_file: existingVideo },
                                        dataType: "json",
                                        success: function (response) {
                                            let $msg = $('#messageBox');
                                            $msg.removeClass('d-none alert-success alert-danger');

                                            if (response.status === "success") {
                                                deletedVideos.push(existingVideo);
                                                $('#deleted_videos').val(deletedVideos.join(","));
                                                allUploadedVideos = allUploadedVideos.filter(v => v !== existingVideo);
                                                row.remove();
                                                updateSerialNumbers();
                                                if (tableBody.find("tr").length === 0) {
                                                    tableBody.append('<tr><td colspan="6" class="no-file">No files selected!</td></tr>');
                                                }
                                                $msg.addClass('alert-success')
                                                    .text('Video Deleted Successfully!')
                                                    .show();

                                                setTimeout(() => $msg.fadeOut(), 2000);
                                                resetFileInput();
                                            } else {

                                                $msg.addClass('alert-danger')
                                                    .text(response.message || 'Error deleting video.')
                                                    .show();

                                                setTimeout(() => $msg.fadeOut(), 2000);
                                            }
                                        },
                                        error: function () {
                                            swal("Error!", "Could not delete video.", { icon: "error" });

                                            let $msg = $('#messageBox');
                                            $msg.removeClass('d-none alert-success')
                                                .addClass('alert-danger')
                                                .text('Error deleting video.')
                                                .show();

                                            setTimeout(() => $msg.fadeOut(), 2000);
                                        }
                                    });
                                }
                            }
                        });
                    });
                }

                function addVideosToTable(videos) {
                    if (!table) createTable();

                    // ✅ Remove "No files selected!" row if present
                    tableBody.find(".no-file").remove();

                    videos.forEach(function (video) {
                        let videoUrl = "<?= base_url('public/uploads/videos/') ?>" + video;
                        let rowIndex = tableBody.find("tr").length + 1;
                        // <td style="width: 100px;>${video}</td>
                        tableBody.append(`
                            <tr data-existing-video="${video}">
                                <td>${rowIndex}</td>
                                <td>
                                    <input type="text" name="lesson_name[]" class="form-control" placeholder="Enter lesson name" required>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="play-video-link text-primary" data-video="${videoUrl}" data-title="${video}">
                                        Play Video <i class="bi bi-play-circle"></i>
                                    </a>
                                </td>
                                <td>video/mp4</td>
                                <td>
                                    <button type="button" class="deleteBtn">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    bindDeleteButtons();
                    // updateSerialNumbers();
                }

                function resetFileInput() {
                    let fileInput = document.querySelector('input[type="file"][name="module_videos[]"]');
                    if (fileInput) {
                        fileInput.value = ''; // Clear file input
                    }
                }

                function uploadFiles(files) {
                    let formData = new FormData();
                    formData.append('module_id', $('#module_id').val() || '');

                    for (let i = 0; i < files.length; i++) {
                        formData.append('module_videos[]', files[i]);
                    }

                    let progressContainer = fileUploadDiv.find(".progress");
                    let progressBar = progressContainer.find(".progress-bar");
                    progressBar.css("width", "0%").text("0%"); // Reset before upload
                    progressContainer.show();
                    $.ajax({
                        url: "<?= base_url('admin/coursemodule/uploadVideo') ?>",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        timeout: 0,
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    let percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                    progressBar.css("width", percentComplete + "%").text(percentComplete + "%");
                                }
                            }, false);
                            return xhr;
                        },
                        success: function (response) {
                            let $msg = $('#messageBox');
                            $msg.removeClass('d-none alert-success alert-danger');

                            // progressBar.css("width", "100%").text("Upload Complete");
                            progressBar.css("width", "100%");
                            setTimeout(() => progressContainer.hide(), 1000);

                            if (response.status === 'success') {
                                $msg.addClass('alert-success').text(response.message || 'Videos uploaded successfully!').show();

                                let newVideos = response.uploaded || [];
                                allUploadedVideos = allUploadedVideos.concat(newVideos);
                                newUploadedVideos = newUploadedVideos.concat(newVideos);

                                $('#uploaded_videos').val(newUploadedVideos.join(','));
                                addVideosToTable(newVideos);

                                setTimeout(() => $msg.fadeOut(), 1500);
                                resetFileInput();
                            } else {
                                $msg.addClass('alert-danger').text(response.message || (response.errors ? response.errors.join(', ') : 'Upload failed')).show();
                                setTimeout(() => $msg.fadeOut(), 2000);
                                resetFileInput();
                            }
                        },
                        error: function () {
                            let $msg = $('#messageBox');
                            $msg.removeClass('d-none alert-success').addClass('alert-danger').text('Error uploading video(s)').show();
                            setTimeout(() => $msg.fadeOut(), 2000);
                            resetFileInput();
                        }
                    });
                }
                function updateSerialNumbers() {
                    tableBody.find("tr").each(function (index) {
                        $(this).find("td:first").text(index + 1);
                    });
                }

                function handleFiles(files) {
                    if (!table) {
                        createTable();
                    }

                    if (files.length > 0) {
                        let previewContainer = $(`#videoPreview_${fileUploadId}`);
                        previewContainer.html("");

                        $.each(files, function (index, file) {
                            if (!file.type.startsWith("video/")) return;

                            let videoPreview = `<video width="200" controls src="${URL.createObjectURL(file)}"></video>`;
                            previewContainer.append(videoPreview);
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

                function addExistingVideos(videos) {
                    if (videos && videos.length) {
                        allUploadedVideos = [];

                        videos.forEach(function (video) {
                            if (!deletedVideos.includes(video)) {
                                allUploadedVideos.push(video);
                            }
                        });

                        if (!table) createTable();
                        tableBody.empty();
                        addVideosToTable(allUploadedVideos);
                    } else {
                        createTable();
                    }
                }

                var existingVideos = $('#existing_videos').val();
                if (existingVideos) {
                    addExistingVideos(existingVideos.split(','));
                } else {
                    createTable();
                }
            });
        };
    })(jQuery);
    $(document).ready(function () {
        $("#fileUpload").fileUpload();
        $('#lessonForm').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const messageBox = $('#messageBox');
            const base_url = "<?= base_url() ?>";

            $.post(base_url + 'admin/module/saveLesson', form.serialize(), function (response) {
                messageBox.removeClass('d-none alert-success alert-danger');

                if (response.status === 'success') {
                    messageBox
                        .addClass('alert-success')
                        .text(response.message)
                        .fadeIn();
                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                } else {
                    messageBox
                        .addClass('alert-danger')
                        .text(response.message || 'Something went wrong.')
                        .fadeIn();
                }
            }, 'json').fail(function () {
                messageBox
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .text('Error submitting the form. Please try again.')
                    .fadeIn();
            });
        });
        // Play video modal
        $(document).on("click", ".play-video-link", function () {
            let videoUrl = $(this).data("video");
            let videoTitle = $(this).data("title");

            $("#videoTitle").text(videoTitle);

            let videoPlayer = $("#videoPlayer")[0];
            $(videoPlayer).find("source").remove();

            let newSource = document.createElement("source");
            newSource.src = videoUrl;
            newSource.type = "video/mp4";
            videoPlayer.appendChild(newSource);

            videoPlayer.load();
            videoPlayer.play();

            const videoModalEl = document.getElementById('videoModal');
            const videoModal = new bootstrap.Modal(videoModalEl);
            videoModal.show();

            $(videoModalEl).on("hidden.bs.modal", function () {
                let videoPlayer = $("#videoPlayer")[0];
                $(videoPlayer).find("source").remove();
                videoPlayer.load();
            });
        });
    });
</script>