<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var markers = [{
                coords: [31.230391, 121.473701],
                name: "Shanghai"
            },
            {
                coords: [28.704060, 77.102493],
                name: "Delhi"
            },
            {
                coords: [6.524379, 3.379206],
                name: "Lagos"
            },
            {
                coords: [35.689487, 139.691711],
                name: "Tokyo"
            },
            {
                coords: [23.129110, 113.264381],
                name: "Guangzhou"
            },
            {
                coords: [40.7127837, -74.0059413],
                name: "New York"
            },
            {
                coords: [34.052235, -118.243683],
                name: "Los Angeles"
            },
            {
                coords: [41.878113, -87.629799],
                name: "Chicago"
            },
            {
                coords: [51.507351, -0.127758],
                name: "London"
            },
            {
                coords: [40.416775, -3.703790],
                name: "Madrid "
            }
        ];
        var map = new jsVectorMap({
            map: "world",
            selector: "#world_map",
            zoomButtons: true,
            markers: markers,
            markerStyle: {
                initial: {
                    r: 9,
                    strokeWidth: 7,
                    stokeOpacity: .4,
                    fill: window.theme.primary
                },
                hover: {
                    fill: window.theme.primary,
                    stroke: window.theme.primary
                }
            },
            zoomOnScroll: false
        });
        window.addEventListener("resize", () => {
            map.updateSize();
        });
    });
</script>
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


{{-- <script>
    document.getElementById('subkontraktor').addEventListener('change', function() {
        var filterValue = this.value.toLowerCase();
        var rows = document.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            var subkontraktorName = row.querySelector('.subkontraktor-column').textContent
        .toLowerCase();
            row.style.display = subkontraktorName.includes(filterValue) ? '' : 'none';
        });
    });

    document.getElementById('exportPDF').addEventListener('click', function() {
        var filterValue = document.getElementById('subkontraktor').value;
        window.location.href = '{{ route('export.pdf') }}?subkontraktor=' + filterValue;
    });
</script> --}}

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
