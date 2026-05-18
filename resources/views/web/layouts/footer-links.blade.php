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

<!-- Fancybox JS -->
<script src="{{ asset('assets/libs/fancybox/jquery.fancybox.min.js') }}"></script>

<script src="{{ asset('assets/libs/axios/axios.min.js') }}{{ ASSETS_VERSION}}"></script>

{{-- map --}}
<script src="{{ asset('assets/libs/leaflet/dist/leaflet.js') }}{{ ASSETS_VERSION }}"></script>
<script src="{{ asset('assets/libs/leaflet/leaflet.markercluster.js')}}{{ ASSETS_VERSION }}"></script>
<script src="{{ asset('assets/libs/leaflet/Control.Geocoder.js') }}{{ ASSETS_VERSION }}"></script>

{{-- alert --}}
<script src="{{ asset('assets/libs/sweetalert/sweetalert.js') }}"></script>

<script>
    window.appUrls = {
        login: "{{ route('organization.login') }}",
        register: "{{ route('organization.register') }}"
    };
</script>
{{-- custom JS --}}
<script src="{{ asset('assets/js/app.js') }}"></script>
<script>    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>