<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Congresso de Famílias 2018 - Inscrições MEUC</title>
	@include('head')

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

		.box>.person+.person>.box-header,
		.box>.box-body+.person>.box-header {
			border-top: 2px solid #e7e7e7;
		}
	</style>
</head>

<body>
	<div id="app">
		<div class="jumbotron">
			<div class="container">
				<div class="row">
					<div class="col-md-8">
						<h2>Inscrições para</h2>
						<h1>{{$evento->nome}}</h1>
						<a class="btn btn-primary" target="_blank" href="http://meuc.org.br/eventos">Ver detalhes sobre o evento</a>
					</div>
					<div class="col-md-4 text-right align-bottom">
						<h4>18 a 20 de Abril de 2017</h4>
						<h4>São Bento do Sul - SC (
							<a href="https://maps.google.com/" target="_blank">Mapa</a>)</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="progress progress-sm active">
				<div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0"
				    aria-valuemax="100" style="width:40%">
					<span class="sr-only">60%</span>
				</div>
			</div>

			<div id="step_2" data-step="2" class="step">
				<responsavel :pesquisa="pesquisa"></responsavel>
				<dependente></dependente>
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
		</div>
	</div>
	</div>
	@include('scripts')
</body>

</html>