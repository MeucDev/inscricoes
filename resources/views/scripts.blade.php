<script src="{{ asset('js/app.js?2020') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/dist/js/app.js') }}" type="text/javascript"></script>

<script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
</script>