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
		.commands {
			margin-bottom: 20px;
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
			<inscricao :evento="{{$evento->id}}"></inscricao>
		</div>
	</div>
	@include('scripts')
</body>

</html>