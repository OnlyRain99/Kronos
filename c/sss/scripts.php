<!-- Jquery JS-->
<script src="../../vendor/jquery-3.2.1.min.js"></script>
<!-- Bootstrap JS-->
<script src="../../vendor/bootstrap-4.1/popper.min.js"></script>
<script src="../../vendor/bootstrap-4.1/bootstrap.min.js"></script>
<!-- Vendor JS       -->
<script src="../../vendor/slick/slick.min.js">
</script>
<script src="../../vendor/wow/wow.min.js"></script>
<script src="../../vendor/animsition/animsition.min.js"></script>
<script src="../../vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
</script>
<script src="../../vendor/counter-up/jquery.waypoints.min.js"></script>
<script src="../../vendor/counter-up/jquery.counterup.min.js">
</script>
<script src="../../vendor/circle-progress/circle-progress.min.js"></script>
<script src="../../vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../../vendor/chartjs/Chart.bundle.min.js"></script>
<script src="../../vendor/select2/select2.min.js"></script>
<script src="../../js/Chart.js"></script>
<script src="../../vendor/sweetalert/sweetalert2.all.min.js" ></script>
<script src="../../vendor/touch-rotation-propeller/propeller.min.js"></script>
<script src="../../vendor/draggingj/jquery.Dragging.js"></script>
<!-- online bootstrap -->
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<!-- Main JS-->
<script src="../../js/main.js"></script>
<script src="../../js/moment.js"></script>

<script type="text/javascript">
    //disable back function
    function preventBack() { window.history.forward(); }
    setTimeout("preventBack()", 0);
    window.onunload = function () { null };
</script>

<script type="text/javascript">
    //modal autofocus
    $(document).on('shown.bs.modal', function() {
      $(this).find('[autofocus]').focus();
      $(this).find('[autofocus]').select();
    });
</script>

<script type="text/javascript">
    //disable f12
    $(document).keydown(function (event) {
        if (event.keyCode == 123) { // Prevent F12
            return false;
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
            return false;
        }
    });

    //disable inspect element
    $(document).on("contextmenu", function (e) {        
        e.preventDefault();
    });

    function _getID(getval){
        
        var idget = document.getElementById(getval);

        return idget;
    }
</script>