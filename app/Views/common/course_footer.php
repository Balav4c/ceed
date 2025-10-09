</body>
<script src="<?php echo base_url().ASSET_PATH; ?>user/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url().ASSET_PATH; ?>user/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url().ASSET_PATH; ?>user/assets/js/app.js"></script>
<script>
  function openRespMenu() {
    document.getElementById("respMenu").style.display = "block";
  }

  function closeRespMenu() {
    document.getElementById("respMenu").style.display = "none";
  }
  $(document).ready(function(){
    let currentPath = window.location.pathname.replace(/\/$/, "");

    $(".menu-item").each(function(){
        let href = $(this).attr("href");
        if (href) {
            let linkPath = new URL(href, window.location.origin).pathname.replace(/\/$/, "");
            if (linkPath === currentPath) {
                $(this).addClass("active");
            }
        }
    })
  });

</script>


</html>