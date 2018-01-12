<template>
<div id="responsavel">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">Dados do responsável</h4>
        </div>
        <div class="person">
            <div class="row box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" v-model="pessoa.cpf" @change="getPessoa" class="form-control" id="cpf">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" v-model="pessoa.nome" class="form-control" id="nome">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" v-model="pessoa.email" class="form-control" id="email">
                    </div>
                </div>
            </div>
            <div class="row box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="datanascimento">Data de Nascimento</label>
                        <input type="text" class="form-control" v-model="pessoa.nascimento" id="nascimento" placeholder="dd/mm/aaaa">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sexo">Sexo</label>
                        <select name="sexo" v-model="pessoa.sexo" id="sexo" class="form-control">
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" v-model="pessoa.telefone" class="form-control" id="telefone">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alojamento">Hospedagem</label>
                        <select name="alojamento" id="alojamento" v-model="pessoa.alojamento" class="form-control">
                            <option value="CAMPING">Camping</option>
                            <option value="LAR">Lar Filadélfia</option>
                            <option value="OUTROS">Outro / Hotel na cidade</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="refeicao">Refeição</label>
                        <select name="refeicao" id="refeicao" v-model="pessoa.refeicao" class="form-control">
                            <option value="QUIOSQUE_COM_CAFE">Quiosque com café</option>
                            <option value="QUIOSQUE_SEM_CAFE">Quiosque sem café</option>
                            <option value="LAR_COM_CAFE">Lar Filadélfia com café</option>
                            <option value="LAR_SEM_CAFE">Lar Filadélfia sem café</option>
                            <option value="NENHUMA">Nenhuma</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>    
	<dependente v-for="dependente in pessoa.dependentes" v-bind:key="dependente.id" :pessoa="dependente"></dependente>
    <div class="commands">
        <button type="button" class="btn btn-success">Adicionar dependente</button>
    </div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data (){
            return{
                pessoa : {cpf:'0444220690'}
            }
        },
        methods: {
            getPessoa: function(){
                // GET /someUrl
                this.$http.get('http://localhost/pessoas/' + this.pessoa.cpf).then(response => {
                    // get body data
                    this.pessoa = response.body;
                }, (error) => {
                    console.log("erro ao carregar pessoa" + error);
                });            
            }
        }
    }
</script>
