@extends("crudbooster::admin_template")
@section("content")
    <h1>Cracha</h1>

	<form id="middle" method="post" action="javascript:generatePdfFromForm()">
		<div class="form-group">
			<label for="event">Nome do evento</label>
			<input type="text" id="event" class="form-control" name="event" value="XX Congresso de Famílias" />
		</div>
		<div class="form-group">
			<label for="nickname">Apelido</label>
			<input type="text" id="nickname" class="form-control" name="nickname" value="Matias" />
		</div>
		<div class="form-group">
			<label for="fullname">Nome completo</label>
			<input type="text" id="fullname" class="form-control" name="fullname" value="Matias Guiomar Henschel" />
		</div>
		<div class="form-group">
			<label for="city">Cidade</label>
			<input type="text" id="city" class="form-control" name="city" value="Blumenau - SC" />
		</div>
		<div class="form-group col-6">
			<label for="eatPlace">Alimentação - Local</label>
			<select name="eatPlace" class="form-control" id="eatPlace">
				<option value="L">Lar</option>
				<option value="Q">Quiosque</option>
			</select>
		</div>
		<div class="form-group col-6">
			<label for="eatGroup">Alimentação - Grupo</label>
			<select name="eatGroup" class="form-control" id="eatGroup">
				<option value="A">A</option>
				<option value="B">B</option>
			</select>
		</div>
		<button type="submit">Imprimir cracha</button>
	</form>    

@endsection