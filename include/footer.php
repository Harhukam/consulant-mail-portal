<!-- Logout Notification Box -->
<div id="logout">
<div class="logout-message">
<img style="height: 120px; width: 120px;" class="img-circle img-logout" src="images/<?php echo $row['adminPhoto']; ?>" alt="<?php echo $row['adminName']; ?>">
<h3>
<i class="fa fa-sign-out text-green"></i> Ready to go?
</h3>
<p>Select "Logout" below if you are ready<br> to end your current session.</p>
<ul class="list-inline">
<li>
<a href="logout" class="btn btn-green">
<strong>Logout</strong>
</a>
</li>
<li>
<button class="logout_close btn btn-green">Cancel</button>
</li>
</ul>
</div>
</div>
<!-- /#logout -->


<!-- Logout Notification jQuery -->
<script src="assset/js/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assset/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assset/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
<script src="assset/js/plugins/popupoverlay/defaults.js"></script>
<script src="assset/js/plugins/popupoverlay/logout.js"></script>
<script src="assset/js/plugins/hisrc/hisrc.js"></script>
<script src="assset/js/flex.js"></script>
<script src="assset/js/plugins/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>
<script src="assset/js/plugins/bootstrap-tokenfield/scrollspy.js"></script>
<script src="assset/js/plugins/bootstrap-tokenfield/affix.js"></script>
<script src="assset/js/plugins/bootstrap-tokenfield/typeahead.min.js"></script>
<script src="assset/js/plugins/bootstrap-maxlength/bootstrap-maxlength.js"></script>
<script src="assset/js/demo/advanced-form-demo.js"></script>
<script src="assset/js/plugins/pace/pace.js"></script>
</div><!-- end MAIN PAGE CONTENT -->  <!-- /#page-wrapper -->
</div><!-- /#wrapper -->
<!--  footer end -->

   <script type="text/javascript">
    function disableRightClick() {
        //alert("ohh! hoo!, right click is not allowed !!");
        return false;
    }
</script>

</body>
</html>

