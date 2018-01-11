<script src="{{ asset('js/app.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/dist/js/app.js') }}" type="text/javascript"></script>

<!--BOOTSTRAP DATEPICKER-->	
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<!--BOOTSTRAP DATERANGEPICKER-->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>

<!-- Bootstrap time Picker -->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('vendor/crudbooster/assets/lightbox/dist/js/lightbox.min.js') }}"></script> 	

<!--SWEET ALERT-->
<script src="{{asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.min.js')}}"></script> 
<script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
</script>