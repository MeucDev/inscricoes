<<template>
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
</template>

