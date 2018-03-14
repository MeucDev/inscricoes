<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	@include('head')
</head>

<body>
    <form method="post">
    {{ csrf_field() }}
    <div class="box box-primary">
        <div class="row box-body">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Presença</th>
                            <th>Nome</th>
                            <th>Idade</th>
                            <th>Alojamento</th>
                            <th>Valor</th>
                            <th>Refeição</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="presenca_{{$inscricao->numero}}" checked></td>
                            <td>{{$inscricao->pessoa->nome}}</td>
                            <td>{{$inscricao->pessoa->idade}}</td>
                            <td>{{$inscricao->alojamento}}</td>
                            <td>{{$inscricao->valorAlojamento}}</td>
                            <td>{{$inscricao->refeicao}}</td>
                            <td>{{$inscricao->valorRefeicao}}</td>
                        </tr>
                        @foreach ($inscricao->dependentes as $dependente)
                            <tr>
                                <td><input type="checkbox" name="presenca_{{$dependente->numero}}" checked></td>
                                <td>{{$dependente->pessoa->nome}}</td>
                                <td>{{$dependente->pessoa->idade}}</td>
                                <td>{{$dependente->alojamento}}</td>
                                <td>{{$dependente->valorAlojamento}}</td>
                                <td>{{$dependente->refeicao}}</td>
                                <td>{{$dependente->valorRefeicao}}</td>
                                </tr>
                        @endforeach                            
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th><h4>Total<small> (valor total da inscrição)</small></h4></th>
                            <td class="text-right"><h4><nobr>{{number_format($inscricao->valorTotal, 2)}}</nobr></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Pago<small> (pago no ato da inscrição)</small></h4></th>
                            <td class="text-right"><h4><nobr>{{number_format($inscricao->valorTotalPago, 2)}}</nobr></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Restante</h4></th>
                            <td class="text-right"><h4><nobr>R$ {{number_format($inscricao->valorTotal - $inscricao->valorPago, 2)}}</nobr></h4></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                    <div class="text-right commands">
                        <button type="submit" id="confirmar" class="btn btn-success btn-lg">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    </form>
    @include('scripts')
</body>

</html>