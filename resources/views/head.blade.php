<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name='robots' content='noindex,nofollow'/>
<link rel="shortcut icon" href="{{ CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>        
<!-- Bootstrap 3.3.2 -->
<link rel="stylesheet" type="text/css" href="{{ asset("vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css") }}" />
<!-- Font Awesome Icons -->
<link rel="stylesheet" type="text/css" href="{{asset("vendor/crudbooster/assets/adminlte/font-awesome/css")}}/font-awesome.min.css" />
<!-- Theme style -->
<link rel="stylesheet" type="text/css" href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css")}}" />    
<link rel="stylesheet" type="text/css" href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/skins/_all-skins.min.css")}}" />
<style>
    .swal2-popup {
        font-size: 1.6rem !important;
        }
</style>
