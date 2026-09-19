<!-- Footer Fixed-to-Bottom Fix -->
<style>
    body.layout-fixed .main-footer {
        position: fixed;
        bottom: 0;
        left: 250px;
        right: 0;
        margin-left: 0 !important;
        z-index: 1030;
        background: #fff;
        transition: left .3s ease-in-out;
    }

    /* Adjust when sidebar is collapsed (AdminLTE mini-sidebar width) */
    body.layout-fixed.sidebar-collapse .main-footer {
        left: 4.6rem;
    }

    /* Prevent page content from being hidden behind the fixed footer */
    body.layout-fixed .content-wrapper {
        padding-bottom: 60px;
    }
</style>

<!-- Main Footer -->
    <footer class="main-footer">

        <strong>

            DPWH Aurora Materials Testing Repository

        </strong>

        <div class="float-right d-none d-sm-inline">

            Version 1.0

        </div>

    </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/proposed_dpwh_repository/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap 4 -->
<script src="/proposed_dpwh_repository/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="/proposed_dpwh_repository/dist/js/adminlte.min.js"></script>

<!-- DataTables -->
<script src="/proposed_dpwh_repository/plugins/datatables/jquery.dataTables.min.js"></script>

<script src="/proposed_dpwh_repository/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="/proposed_dpwh_repository/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>

<script src="/proposed_dpwh_repository/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- SweetAlert2 -->
<script src="/proposed_dpwh_repository/plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- Toastr -->
<script src="/proposed_dpwh_repository/plugins/toastr/toastr.min.js"></script>

<script>

$(function(){

    $(".datatable").DataTable({

        responsive:true,
        autoWidth:false,
        pageLength:10

    });

});

</script>

</body>

</html>