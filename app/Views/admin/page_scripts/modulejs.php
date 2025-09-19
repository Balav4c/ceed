<script>
    $(document).ready(function () {
        $('.content').richText();
        $("#fileUpload").fileUpload({
            allowedTypes: ['video/mp4', 'video/webm', 'video/ogg'], // only video files
            onError: function (file) {
                alert('Only video files are allowed: ' + file.name);
            }
        });

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

        $moduleForm.on('submit', function (e) {
            e.preventDefault();

            let valid = true;
            let firstEmptyName = false;

            $('.module-item').each(function () {
                let moduleName = $(this).find('input[name="module_name[]"]').val().trim();
                if (moduleName === '') {
                    valid = false;
                    if (!firstEmptyName) {
                        firstEmptyName = true;
                        $messageBox.removeClass('d-none alert-success alert-danger')
                            .addClass('alert-danger')
                            .text('Please fill all mandatory Module Name fields')
                            .show();
                    }
                }

                let $videoInput = $(this).find('input[type="file"]');
                $videoInput.each(function () {
                    let files = this.files;
                    for (let i = 0; i < files.length; i++) {
                        if (!files[i].type.startsWith('video/')) {
                            valid = false;
                            $messageBox.removeClass('d-none alert-success alert-danger')
                                .addClass('alert-danger')
                                .text('Only video files are allowed for Module Video')
                                .show();
                        }
                    }
                });
            });

            if (!valid) return;

            $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });

            var formData = new FormData(this);
            $.ajax({
                url: $moduleForm.attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success' || response.status == 1) {
                        $messageBox.removeClass('d-none alert-success alert-danger')
                            .addClass('alert-success')
                            .text(response.msg || 'Modules Added Successfully!')
                            .show();

                        setTimeout(function () {
                            window.location.href = "<?= base_url('admin/manage_module'); ?>";
                        }, 1500);
                    } else {
                        $messageBox.removeClass('d-none alert-success alert-danger')
                            .addClass('alert-danger')
                            .text(response.message || 'Something went wrong')
                            .show();
                        enableSaveButton();
                    }

                    setTimeout(function () {
                        $messageBox.fadeOut();
                    }, 2000);
                },
                error: function () {
                    $messageBox.removeClass('d-none alert-success alert-danger')
                        .addClass('alert-danger')
                        .text('Error submitting the form. Please try again.')
                        .show();
                    enableSaveButton();
                    setTimeout(function () {
                        $messageBox.fadeOut();
                    }, 2000);
                }
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
                        render: function (data) {
                            if (!data) return 'N/A';
                            let videos = data.split(',');
                            let html = videos.map(v => {
                                v = v.trim();
                                return `<a href="<?= base_url('uploads/videos/') ?>${v}" target="_blank">${v}</a>`;
                            }).join('<br>');

                            return html;
                        }
                    },

                    {
                        data: "status",
                        render: function (data, type, row) {
                            let checked = data == 1 ? 'checked' : '';
                            return `<div class="form-check form-switch">
                        <input class="form-check-input toggle-module-status" type="checkbox" data-id="${row.module_id}" ${checked}>
                    </div>`;
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
                        <a href="javascript:void(0);" class="view-module-details" data-id="${id}" title="View Module Details" style="color:green;">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </div>`;
                        }
                    }
                ],
                order: [[1, 'asc']], // FIXED
                columnDefs: [
                    { searchable: false, orderable: false, targets: [0, 4, 5, 6] } // allow search in description
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