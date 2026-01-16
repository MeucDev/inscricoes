<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>{{$evento->nome}}</title>
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
	<!-- Google Analytics -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		
		ga('create', 'UA-382004-13', 'auto');
		ga('send', 'pageview');
	</script>
	<!-- End Google Analytics -->	
</head>

<body>
	<div id="app">
		<div class="jumbotron">
			<div class="container">
				<div class="row">
					<div class="col-md-8">
						<h2>Inscrições para</h2>
						<h1>{{$evento->nome}}</h1>
						<a class="btn btn-primary" target="_blank" href="{{$evento->linkDetalhes}}">Ver detalhes sobre o evento</a>
					</div>
					<div class="col-md-4 text-right align-bottom">
						<h4>{{$evento->periodo}}</h4>
						<h4>{{$evento->local}} (<a href="{{$evento->linkMapa}}" target="_blank">Mapa</a>)</h4>
						<img src="{{ asset('images/congresso.png') }}" />
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<inscricao :evento="{{json_encode([
				'id' => $evento->id,
				'nome' => $evento->nome,
				'registrar_data_casamento' => isset($evento->registrar_data_casamento) ? $evento->registrar_data_casamento : 1
			])}}" :tipo-inscricao-link="{{isset($tipoInscricao) ? json_encode($tipoInscricao) : 'null'}}"></inscricao>
		</div>
	</div>
	@include('scripts')
</body>

</html>