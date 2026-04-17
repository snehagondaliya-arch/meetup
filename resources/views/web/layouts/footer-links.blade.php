<!-- Jquery-cdn-->
<script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap-5 -->
<script src="{{ asset('assets/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- AOS Animation -->
<script src="{{ asset('assets/libs/aos-animation/aos.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<!-- Swiper JS -->
<script src="{{ asset('assets/libs/swiper-slider/swiper-bundle.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>