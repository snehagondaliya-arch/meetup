<!-- Jquery-cdn-->
<script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}{{ ASSETS_VERSION }}"></script>
<!-- Bootstrap-5 -->
<script src="{{ asset('assets/libs/bootstrap/bootstrap.bundle.min.js') }}{{ ASSETS_VERSION }}"></script>
<!-- AOS Animation -->
<script src="{{ asset('assets/libs/aos-animation/aos.js') }}{{ ASSETS_VERSION }}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/libs/select2/select2.min.js') }}{{ ASSETS_VERSION }}"></script>
<!-- Swiper JS -->
<script src="{{ asset('assets/libs/swiper-slider/swiper-bundle.min.js') }}{{ ASSETS_VERSION }}"></script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js{{ ASSETS_VERSION }}"></script>

<script src="{{ asset('assets/js/app.js') }}"></script>

{{-- map --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js{{ ASSETS_VERSION }}"></script>
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js{{ ASSETS_VERSION }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js{{ ASSETS_VERSION }}"></script>

{{-- datatable --}}
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js{{ ASSETS_VERSION }}"></script>


<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://unpkg.com/leaflet-control-search/dist/leaflet-search.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>