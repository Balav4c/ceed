<script>
      $(document).ready(function(){

    // Add new module
    $('#addModule').click(function(){
        let moduleItem = $('.module-item:first').clone(); 
        moduleItem.find('input, textarea').val('');      
        $('#module-container').append(moduleItem);      
    });

    // Remove module
    $(document).on('click', '.remove-module', function(){
        if($('.module-item').length > 1){  
            $(this).closest('.module-item').remove();
        } else {
            alert('At least one module is required.');
        }
    });

});
</script>