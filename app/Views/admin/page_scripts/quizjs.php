<script>
$(function(){
    $('#quizTable').DataTable({
    ajax: {
        url: "<?= base_url('admin/mini_quiz/listajax') ?>",
        type: "POST",
        dataSrc: "data"
    },
    columns: [
        { data: "quiz_id" },
        { data: "course_id" },
        { data: "module_id" },
        { data: "question_text" },
        { data: "correct_answer" },
        {
            data: "quiz_id",
            render: function(id) {
                let editUrl = "<?= base_url('admin/mini_quiz/edit/') ?>" + id;
                return `
                    <a href="${editUrl}" class="btn btn-sm btn-info me-1">
                        Edit
                    </a>
                    <button class="btn btn-sm btn-danger deleteQuiz" data-id="${id}">Delete</button>`;
            }
        }
    ]
});


    $(document).on('click', '.deleteQuiz', function(){
        let id = $(this).data('id');
        if(confirm('Are you sure you want to delete this quiz?')){
            $.post("<?= base_url('admin/mini_quiz/delete') ?>", {id: id}, function(res){
                if(res.status === 'success') table.ajax.reload();
            });
        }
    });
});
</script>