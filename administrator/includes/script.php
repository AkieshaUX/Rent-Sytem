<script type="text/javascript" src="../../dist/js/jquery.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../../plugins/raphael/raphael.min.js"></script>
<script src="../../plugins/chart.js/Chart.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<script src="../../plugins/filterizr/jquery.filterizr.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<script src="../dist/js/sweetalert2.all.min.js"></script>
<script src="../../plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../../plugins/jquery-mapael/maps/usa_states.min.js"></script>
<script src="../../dist/js/select2.js"></script>
<script src="../../dist/manific/jquery.magnific-popup.min.js"></script>
<script src="../../inc/main.js"></script>
<script>
  $('#scannerQR').submit(function(e) {
    e.preventDefault();
    const form_data = new FormData(this);
    $.ajax({
      url: '../inc/qrcode.php',
      type: 'POST',
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        $('#scannerQR')[0].reset();
        $('#example1').load('In-Out.php #example1');
        $('#loadpageScan').load('dashboard.php #loadpageScan');
      },
      error: function() {
        console.error('Error occurred while processing the QR code.');
      }
    });
  });
  setInterval(function() {
    fetch('../inc/upcoming_send_sms.php')
      .then(response => response.text())
      .then(data => console.log("SMS Script executed:", data))
      .catch(error => console.error("Error:", error));
  }, 60000);
  setInterval(function() {
    fetch('../inc/current_send_sms.php')
      .then(response => response.text())
      .then(data => console.log("SMS Script executed:", data))
      .catch(error => console.error("Error:", error));
  }, 60000);
  setInterval(function() {
    fetch('../inc/duedate_send_sms.php')
      .then(response => response.text())
      .then(data => console.log("SMS Script executed:", data))
      .catch(error => console.error("Error:", error));
  }, 60000);
  setInterval(function() {
    fetch('../inc/curfew.php')
      .then(response => response.text())
      .then(data => console.log("SMS Script executed:", data))
      .catch(error => console.error("Error:", error));
  }, 60000);
  $(document).ready(function() {
    $("table tr").each(function(index) {
      $(this).delay(index * 100).queue(function(next) {
        $(this).addClass("fade-in");
        next();
      });
    });
  });
  let isInitialized = false;
  function initializeDataTable() {
    if (window.innerWidth > 768) {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    } else {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": false
      });
    }
    isInitialized = true;
  }
  function handleResize() {
    if (isInitialized) {
      $("#example1").DataTable().destroy();
      isInitialized = false;
    }
    initializeDataTable();
  }
  $(document).ready(function() {
    initializeDataTable();
    let resizeTimeout;
    $(window).resize(function() {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(handleResize, 250);
    });
  });
  $(function() {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });
    $('.filter-container').filterizr({
      gutterPixels: 3
    });
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })
</script>