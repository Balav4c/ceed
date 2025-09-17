<footer>
    <div class="container-lg">
        <div class="col-md-12">
            <div class="row">
                <div class="col-2 foot-logo">
                    <img src="<?php echo base_url().ASSET_PATH; ?>assets/img/logo-footer.png" />
                </div>
                <div class="col-10 social-group">
                    <img class="social-ico" src="<?php echo base_url().ASSET_PATH; ?>assets/img/instagram.png" />
                    <img class="social-ico" src="<?php echo base_url().ASSET_PATH; ?>assets/img/snapchat.png" />
                    <img class="social-ico" src="<?php echo base_url().ASSET_PATH; ?>assets/img/facebook.png" />
                    <img class="social-ico" src="<?php echo base_url().ASSET_PATH; ?>assets/img/youtube.png" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 copyright-txt">
                    Copyright &copy; 2025 Nexarion Innovations. All rights reserved.
                </div>
                <div class="col-md-3 text-center copyright-txt">Terms & Conditions</div>
                <div class="col-md-3 text-right copyright-txt">Privacy Policy</div>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
    </div>
</footer>
</body>
<script src="<?php echo base_url().ASSET_PATH; ?>assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url().ASSET_PATH; ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url().ASSET_PATH; ?>assets/js/app.js"></script>
<script>
function openRestMenu() {
    $('.resp-menu').toggle();
}
$(document).ready(function() {
    $('.menu-header span').on('click', function() {
        $('.resp-menu').toggle();
    })
})
</script>

</html>