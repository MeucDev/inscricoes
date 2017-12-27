<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Nome do evento - Inscrições MEUC</title>
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
	</head>
	<body>
		<div class="jumbotron">
			<div class="container">
				<h2>Inscrições para</h2>
				<h1>Nome do Evento</h1>
				<a class="btn btn-primary" target="_blank" href="http://meuc.org.br/eventos">Ver detalhes sobre o evento</a>
			</div>
		</div>
		<div class="container">
			<div class="progress">
				<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:60%">
					<span class="sr-only">60%</span>
				</div>
			</div>
			<form role="form">
				<div id="step_1" data-step="1" class="row step">
					<h4>Dados do responsável</h4>
					<div class="col-md-6">
						<div class="form-group">
							<label for="cpf">CPF</label>
							<input type="text" class="form-control" id="cpf">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" id="email">
						</div>
					</div>
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary">Iniciar</button>
					</div>
				</div>
				<div id="step_2" data-step="2" class="row step">
					<h4>Dados individuais</h4>
					<ul class="list-group">
						<li class="list-group-item">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nome">Nome</label>
									<input type="text" class="form-control" id="nome">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="cpf">CPF</label>
									<input type="text" class="form-control" id="cpf">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email">Email</label>
									<input type="email" class="form-control" id="email">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="telefone">Telefone</label>
									<input type="text" class="form-control" id="telefone">
								</div>
							</div>
							<div class="clearfix"></div>
						</li>
						<li class="list-group-item">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nome">Nome</label>
									<input type="text" class="form-control" id="nome">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="cpf">CPF</label>
									<input type="text" class="form-control" id="cpf">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email">Email</label>
									<input type="email" class="form-control" id="email">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="telefone">Telefone</label>
									<input type="text" class="form-control" id="telefone">
								</div>
							</div>
							<div class="clearfix"></div>
						</li>
						<li class="list-group-item">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nome">Nome</label>
									<input type="text" class="form-control" id="nome">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="cpf">CPF</label>
									<input type="text" class="form-control" id="cpf">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email">Email</label>
									<input type="email" class="form-control" id="email">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="telefone">Telefone</label>
									<input type="text" class="form-control" id="telefone">
								</div>
							</div>
							<div class="clearfix"></div>
						</li>
					</ul>
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary">Cadastrar</button>
					</div>
				</div>
			</form>
		</div>
		@include('crudbooster::admin_template_plugins')
	</body>