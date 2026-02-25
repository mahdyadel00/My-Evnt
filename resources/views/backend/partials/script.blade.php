<script src="{{ asset('backend/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/libs/pickr/pickr.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('backend/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js') }}"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons/datatables-buttons.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/jszip/jszip.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/pdfmake/pdfmake.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons/buttons.html5.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons/buttons.print.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/select2/select2.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
<!-- <script src="{{ asset('backend') }}/assets/js/app-ecommerce-order-list.js"></script> -->
<!-- Main JS -->
<script src="{{ asset('backend/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('backend/assets/js/dashboards-analytics.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<!-- A friendly reminder to run on a server, remove this during the integration. -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.onload = function () {
        if (window.location.protocol === "file:") {
            alert("This sample requires an HTTP server. Please serve this file with a web server.");
        }
    };
</script>
<script>
    $(document).ready(function () {
        var table = $('#example2').DataTable({
            responsive: true,
            lengthChange: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[1, 'asc']],
            paging: true,
            info: true,
            language: {
                search: "Search sliders:",
                emptyTable: "No sliders available",
                info: "Showing _START_ to _END_ of _TOTAL_ sliders"
            }
        });
        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete "${name}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
                Swal.fire(
                    'Deleted!',
                    `"${name}" has been deleted successfully.`,
                    'success'
                );
            }
        });
    }
    // SweetAlert2 for form submission confirmation
    document.getElementById('edit').addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save this data?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
                Swal.fire(
                    'Saved!',
                    'The data has been saved successfully.',
                    'success'
                );
            }
        });
    });

    // SweetAlert2 for form submission confirmation
    document.getElementById('add').addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save this data?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
                Swal.fire(
                    'Saved!',
                    'The data has been saved successfully.',
                    'success'
                );
            }
        });
    });


</script>