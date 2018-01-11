<template>
<div class="box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">Dados do responsável</h4>
    </div>
    <div class="row box-body">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" :value="pesquisa.cpf" class="form-control" id="cpf">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" :value="pesquisa.email" class="form-control" id="email">
            </div>
        </div>
    </div>
    <div class="text-right">
        <button type="button" class="btn btn-primary" @click="getPessoa">Avançar</button>
    </div>

    <div class="person">
        <div class="box-header with-border">
            <span class="badge bg-light-blue">1</span>
            <span class="badge bg-green">Responsável</span>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="row box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" :value="pessoa.nome" class="form-control" id="nome">
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
</div>    
</template>

<script>
    export default {
        props: ['pesquisa'],
        mounted() {
            console.log('Component mounted.')
        },
        data (){
            return{
                pessoa : {}
            }
        },
        methods: {
            getPessoa: function(){
                // GET /someUrl
                this.$http.get('http://localhost/pessoas/' + this.pesquisa.cpf).then(response => {
                    // get body data
                    this.pessoa = response.body;
                }, (error) => {
                    console.log("erro ao carregar pessoa" + error);
                });            
            }
        }
    }
</script>
