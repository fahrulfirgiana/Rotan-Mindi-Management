<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
        var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
        document.getElementById("datetimepicker-dashboard").flatpickr({
            inline: true,
            prevArrow: "<span title=\"Previous month\">&laquo;</span>",
            nextArrow: "<span title=\"Next month\">&raquo;</span>",
            defaultDate: defaultDate
        });
    });
</script>

<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }

        reader.readAsDataURL(file);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var sidebarItems = document.querySelectorAll('.sidebar-item');

        // Function to remove 'active' class from all items
        function removeActiveClasses() {
            sidebarItems.forEach(function(item) {
                item.classList.remove('active');
            });
        }

        // Function to set 'active' class based on current URL
        function setActiveItem() {
            var currentUrl = window.location.href;
            sidebarItems.forEach(function(item) {
                var link = item.querySelector('a');
                if (link && link.href === currentUrl) {
                    removeActiveClasses(); // Ensure all other active classes are removed
                    item.classList.add('active');
                }
            });
        }

        // Initially set the active item based on URL
        setActiveItem();

        // Add click event listeners to update the 'active' class
        sidebarItems.forEach(function(item) {
            item.addEventListener('click', function() {
                removeActiveClasses();
                this.classList.add('active');
            });
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // JavaScript to toggle column visibility
    document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var columnClass = this.getAttribute('data-column');
            var columns = document.querySelectorAll('.' + columnClass);
            columns.forEach(function(column) {
                column.classList.toggle('hidden', !checkbox.checked);
            });
        });
    });

    // Toggle all columns visibility based on "Tampilkan Semua" checkbox
    document.getElementById('toggle-all').addEventListener('change', function() {
        var isChecked = this.checked;
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            checkbox.checked = isChecked;
            var columnClass = checkbox.getAttribute('data-column');
            var columns = document.querySelectorAll('.' + columnClass);
            columns.forEach(function(column) {
                column.classList.toggle('hidden', !isChecked);
            });
        });
    });

    // Initial column visibility based on checkbox state
    document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
        checkbox.checked = false; // All columns are hidden by default
        var columnClass = checkbox.getAttribute('data-column');
        var columns = document.querySelectorAll('.' + columnClass);
        columns.forEach(function(column) {
            column.classList.add('hidden');
        });
    });
</script>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        function confirmation(event) {
            event.preventDefault();
            const urlToRedirect = event.currentTarget.getAttribute('href');

            swalWithBootstrapButtons.fire({
                title: "Yakin ingin menghapus data ini?",
                text: "Data tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mengonfirmasi penghapusan
                    swalWithBootstrapButtons.fire(
                        'Dihapus!',
                        'Data telah dihapus.',
                        'Berhasil'
                    ).then(() => {
                        // Redirect setelah penghapusan dikonfirmasi
                        window.location.href = urlToRedirect;
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Batal menghapus
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Data batal dihapus :)',
                        'error'
                    );
                }
            });
        }
    </script>