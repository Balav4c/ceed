<script>
      $(document).ready(function(){

    // Add new module
    $('#addModule').click(function(){
        let moduleItem = $('.module-item:first').clone(); // clone first module
        moduleItem.find('input, textarea').val('');      // clear values
        $('#module-container').append(moduleItem);       // append to container
    });

    // Remove module
    $(document).on('click', '.remove-module', function(){
        if($('.module-item').length > 1){   // at least one module must remain
            $(this).closest('.module-item').remove();
        } else {
            alert('At least one module is required.');
        }
    });

});
</script>