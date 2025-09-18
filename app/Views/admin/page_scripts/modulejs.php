<script>
    $(document).ready(function () {
        $('.content').richText();
        $("#fileUpload").fileUpload();
        // Add new module
        $('#addModule').click(function () {
            let moduleItem = $('.module-item:first').clone();
            moduleItem.find('input, textarea').val('');
            $('#module-container').append(moduleItem);
        });

        // Remove module
        $(document).on('click', '.remove-module', function () {
            if ($('.module-item').length > 1) {
                $(this).closest('.module-item').remove();
            } else {
                alert('At least one module is required.');
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