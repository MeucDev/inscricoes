<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Pagamento - Inscrições MEUC</title>
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<meta name='robots' content='noindex,nofollow'/>
		<link rel="shortcut icon" href="{{ CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>        
		<!-- Bootstrap 3.3.2 -->
		<link href="{{ asset("vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
		<!-- Font Awesome Icons -->
		<link href="{{asset("vendor/crudbooster/assets/adminlte/font-awesome/css")}}/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!-- Ionicons -->
		<link href="{{asset("vendor/crudbooster/ionic/css/ionicons.min.css")}}" rel="stylesheet" type="text/css" />
		<!-- Theme style -->
		<link href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />    
		<link href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/skins/_all-skins.min.css")}}" rel="stylesheet" type="text/css" />
		
		<style media="all">
			body {
				background: #eee;
			}
			
			.jumbotron {
				background: white;
			}
			
			.jumbotron h1,
			.jumbotron h2 {
				margin: 0;
			}
			
			.jumbotron a.btn {
				margin-top: 20px;
			}
		</style>
	</head>
	<body>
		<div class="jumbotron">
			<div class="container">
				<h2>Resumo da Inscrição</h2>
				<h1>#142</h1>
				<h5>Para o Congresso de Famílias 2018</h5>
			</div>
		</div>
		<div class="container">
			<div class="progress progress-sm active">
				<div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
					<span class="sr-only">80%</span>
				</div>
			</div>
			<form role="form">
				<div class="callout callout-warning">
					<h4>Falta pouco...</h4>
					<p>Para garantir sua vaga no Congresso de Famílias 2018, não esqueça de efetuar seu pagamento.</p>
				</div>
				<div class="callout callout-success">
					<h4>Seu pagamento já foi identificado.</h4>
					<p>Obrigado! Esperamos você no dia 18 de Abril.</p>
				</div>
				<div class="text-right">
					<button type="button" class="btn btn-success btn-lg">Ir para o pagamento</button>
				</div>
				<div class="box">
					<div class="box-header with-border">
						<h4 class="box-title">Resumo da inscrição</h4>
					</div>
					<div class="row box-body">
						<div class="col-md-6">
							<div class="form-group">
								<label for="cpf">CPF</label>
								<input type="text" class="form-control" id="cpf" value="012.345.678-90" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" value="leandro.piscke@gmail.com" disabled>
							</div>
						</div>
						<div class="col-md-12">
							Mais detalhes blah blah blah
						</div>
					</div>
				</div>
				<div class="text-right">
					<button type="button" class="btn btn-success">Ir para o pagamento</button>
				</div>
			</form>
		</div>
		@include('crudbooster::admin_template_plugins')
	</body>