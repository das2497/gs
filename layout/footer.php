<?php

function html_footer() {
echo <<<EOT

<!-- ======= Footer ======= -->
<br><br><br><br><br>
<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span> Inventory System | Admin Panel</span></strong>. All Rights Reserved
  </div>
  <div class="credits">
    Designed by <a href="#">Inventory System</a>
  </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


<script src="assets/js/main.js"></script>
<script src="assets/js/jquery.min.js"></script>


<!-- Template Main JS File -->

<script>
// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable(
  );
});
</script>

<script>
$(document).ready(function () {
    $("#Notification").load("layout/nort.php");
});
</script>

</body>

</html>
EOT;
}
?>