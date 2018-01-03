<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Congresso de Famílias 2018 - Inscrições MEUC</title>
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
			
			.progress {
				background: #ddd;
			}
			
			.box > .person + .person > .box-header,
			.box > .box-body + .person > .box-header {
				border-top: 2px solid #e7e7e7;
			}
		</style>
	</head>
	<body>
		<div class="jumbotron">
			<div class="container">
				<div class="row">
					<div class="col-md-8">
						<h2>Inscrições para</h2>
						<h1>Congresso de Famílias 2018</h1>
						<a class="btn btn-primary" target="_blank" href="http://meuc.org.br/eventos">Ver detalhes sobre o evento</a>
					</div>
					<div class="col-md-4 text-right align-bottom">
						<h4>18 a 20 de Abril de 2017</h4>
						<h4>São Bento do Sul - SC (<a href="https://maps.google.com/" target="_blank">Mapa</a>)</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="progress progress-sm active">
				<div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
					<span class="sr-only">60%</span>
				</div>
			</div>
			<form role="form">
				<div id="step_1" data-step="1" class="step">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h4 class="box-title">Dados do responsável</h4>
						</div>
						<div class="row box-body">
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
						</div>
					</div>
					<div class="text-right">
						<button type="button" class="btn btn-primary">Avançar</button>
					</div>
				</div>
				<div id="step_2" data-step="2" class="step">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h4 class="box-title">Dados individuais</h4>
						</div>
						<div class="person">
							<div class="box-header with-border">
								<span class="badge bg-light-blue">1</span>
								<span class="badge bg-green">Responsável</span>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="row box-body">
								<div class="col-md-6">
									<div class="form-group">
										<label for="nome">Nome</label>
										<input type="text" class="form-control" id="nome">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="cpf">CPF</label>
										<input type="text" class="form-control" id="cpf">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="datanascimento">Data de Nascimento</label>
										<input type="text" class="form-control" id="datanascimento" placeholder="dd/mm/aaaa">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="sexo">Sexo</label>
										<select name="sexo" id="sexo" class="form-control">
											<option value="m">Masculino</option>
											<option value="f">Feminino</option>
										</select>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email">
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for="ddd">DDD</label>
										<input type="ddd" class="form-control" id="ddd" maxlength="2">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="telefone">Telefone</label>
										<input type="text" class="form-control" id="telefone">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="hospedagem">Hospedagem</label>
										<select name="hospedagem" id="hospedagem" class="form-control">
											<option value="0">Camping</option>
											<option value="1">Lar Filadélfia</option>
											<option value="2">Outro / Hotel na cidade</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="alimentacao">Alimentação</label>
										<select name="alimentacao" id="alimentacao" class="form-control">
											<option value="0">Quiosque</option>
											<option value="1">Lar Filadélfia</option>
											<option value="2">Outro / Não necessito</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="person">
							<div class="box-header with-border">
								<span class="badge bg-light-blue">2</span>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="row box-body">
								<div class="col-md-6">
									<div class="form-group">
										<label for="nome">Nome</label>
										<input type="text" class="form-control" id="nome">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="parentesco">Parentesco</label>
										<select name="parentesco" id="parentesco" class="form-control">
											<option value="0">Cônjuge</option>
											<option value="1">Filho(a)</option>
											<option value="2">Pai/Mãe</option>
											<option value="3">Outro</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="datanascimento">Data de Nascimento</label>
										<input type="text" class="form-control" id="datanascimento" placeholder="dd/mm/aaaa">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="hospedagem">Hospedagem</label>
										<select name="hospedagem" id="hospedagem" class="form-control">
											<option value="0">Camping</option>
											<option value="1">Lar Filadélfia</option>
											<option value="2">Outro / Hotel na cidade</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="alimentacao">Alimentação</label>
										<select name="alimentacao" id="alimentacao" class="form-control">
											<option value="0">Quiosque</option>
											<option value="1">Lar Filadélfia</option>
											<option value="2">Outro / Não necessito</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="person">
							<div class="box-header with-border">
								<span class="badge bg-light-blue">3</span>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="row box-body">
								<div class="col-md-6">
									<div class="form-group">
										<label for="nome">Nome</label>
										<input type="text" class="form-control" id="nome">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="parentesco">Parentesco</label>
										<select name="parentesco" id="parentesco" class="form-control">
											<option value="0">Cônjuge</option>
											<option value="1">Filho(a)</option>
											<option value="2">Pai/Mãe</option>
											<option value="3">Outro</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="datanascimento">Data de Nascimento</label>
										<input type="text" class="form-control" id="datanascimento" placeholder="dd/mm/aaaa">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="hospedagem">Hospedagem</label>
										<select name="hospedagem" id="hospedagem" class="form-control">
											<option value="0">Camping</option>
											<option value="1">Lar Filadélfia</option>
											<option value="2">Outro / Hotel na cidade</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="alimentacao">Alimentação</label>
										<select name="alimentacao" id="alimentacao" class="form-control">
											<option value="0">Quiosque</option>
											<option value="1">Lar Filadélfia</option>
											<option value="2">Outro / Não necessito</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="box-footer text-right">
							<button type="button" class="btn btn-success">Adicionar</button>
						</div>
					</div>
					<div class="text-right">
						<button type="button" class="btn btn-primary">Avançar</button>
					</div>
				</div>
				<div id="step_3" data-step="3" class="step">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h4 class="box-title">Detalhes do Pagamento</h4>
						</div>
						<div class="row box-body">
							<div class="col-md-6">
								<div class="form-group">
									<label for="faturarcontra">Faturar contra</label>
									<select name="faturarcontra" id="faturarcontra" class="form-control">
										<option value="0">Nome do Responsável</option>
										<option value="1">Outro</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="recibo">Necessito de recibo?</label>
									<select name="recibo" id="recibo" class="form-control">
										<option value="0">Nome do Responsável</option>
										<option value="1">Outro</option>
									</select>
								</div>
							</div>
						</div>
						<div class="person">
							<div class="box-header with-border">
								<h5 class="box-title">Responsável pelo pagamento</h5>
							</div>
							<div class="row box-body">
								<div class="col-md-3">
									<div class="form-group">
										<label for="tipopessoa">Natureza</label>
										<select name="tipopessoa" id="tipopessoa" class="form-control">
											<option value="m">Pessoa Física</option>
											<option value="f">Pessoa Jurídica</option>
										</select>
									</div>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<label for="nome">Nome / Razão Social</label>
										<input type="text" class="form-control" id="nome">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="cpf">CPF / CNPJ</label>
										<input type="text" class="form-control" id="cpf">
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email">
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for="ddd">DDD</label>
										<input type="ddd" class="form-control" id="ddd" maxlength="2">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="telefone">Telefone</label>
										<input type="text" class="form-control" id="telefone">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="text-right">
						<button type="button" class="btn btn-primary">Avançar</button>
					</div>
				</div>
			</form>
		</div>
		@include('crudbooster::admin_template_plugins')
	</body>