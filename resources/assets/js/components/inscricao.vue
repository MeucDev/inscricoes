<template>
<div id="inscricao">
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
                    <div :class="{'form-group has-error': errors.has('email') }">
                        <label for="email">Email</label>
                        <input type="email" v-validate="'required|email'" class="form-control" id="email">
                        <span v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}</span>                        
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="datanascimento">Data de Nascimento</label>
                        <input type="text" class="form-control" @change="getValor(pessoa, 'R')" v-model="pessoa.nascimento" id="nascimento" placeholder="dd/mm/aaaa">
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
            </div>
        </div>
        <div class="box-header with-border">
            <h4 class="box-title">Endereço</h4>
        </div>
        <div class="row box-body">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" v-model="pessoa.cep" class="form-control" id="cep">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="uf">UF</label>
                    <input type="text" v-model="pessoa.uf" class="form-control" id="uf">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" v-model="pessoa.cidade" class="form-control" id="cidade">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <input type="text" v-model="pessoa.bairro" class="form-control" id="bairro">
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" v-model="pessoa.endereco" class="form-control" id="endereco">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="nroend">Número</label>
                    <input type="text" v-model="pessoa.nroend" class="form-control" id="nroend">
                </div>
            </div>
        </div>  
        <div class="box-header with-border">
            <h4 class="box-title">Opções</h4>
        </div>
        <div class="row box-body">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="alojamento">Hospedagem</label>
                    <select name="alojamento" id="alojamento" @change="getValor(pessoa, 'R')" v-model="pessoa.alojamento" class="form-control">
                        <option value="CAMPING">Camping</option>
                        <option value="LAR">Lar Filadélfia</option>
                        <option value="OUTROS">Outro / Hotel na cidade</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="refeicao">Refeição</label>
                    <select name="refeicao" id="refeicao" @change="getValor(pessoa, 'R')" v-model="pessoa.refeicao" class="form-control">
                        <option value="QUIOSQUE_COM_CAFE">Quiosque com café</option>
                        <option value="QUIOSQUE_SEM_CAFE">Quiosque sem café</option>
                        <option value="LAR_COM_CAFE">Lar Filadélfia com café</option>
                        <option value="LAR_SEM_CAFE">Lar Filadélfia sem café</option>
                        <option value="NENHUMA">Nenhuma</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-right">
                    <h2>{{"R$ " + formatPrice(pessoa.valor)}}</h2>
                </div>
            </div>
        </div>
    </div>    
	<dependente v-for="dependente in pessoa.dependentes" v-bind:key="dependente.id" 
        :pessoa="dependente" 
        :remove="removeDependente"
        :getvalor = "getValor"
        >
    </dependente>
    <div class="text-right commands">
        <button type="button" class="btn btn-primary" @click="addDependente">Adicionar dependente</button>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">Valor total da inscrição</h4>
        </div>
        <div class="row box-body">
            <div class="col-md-6">
                <h1>{{"R$ " + getValorTotal()}}</h1>
            </div>
            <div class="col-md-6">
                <div class="text-right commands">
                    <button type="button" class="btn btn-success btn-lg" @click="fazerIncricao">Fazer inscrição</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import helpers from './helpers'
    import dependente from './dependente.vue'

    Vue.use(VeeValidate);    
    
    export default {
        props: ['evento'],
        mixins: [helpers],
        components: {dependente},
        
        mounted() {
            console.log('Component mounted.')
        },
        data (){
            return{
                pessoa : {cpf:'0444220690', valor : 0, valorTotal : 0, dependentes: []}
            }
        },
        methods: {
            addDependente: function(){
                this.pessoa.dependentes.push({
                    id: this.pessoa.dependentes.length -100,
                    alojamento: this.pessoa.alojamento,
                    valor: 0,
                    refeicao: this.pessoa.refeicao
                });
            },
            removeDependente: function(id){
                this.pessoa.dependentes = this.pessoa.dependentes.filter(function( obj ) {
                    return obj.id !== id;
                })
            },
            getPessoa: function(){
                this.$http.get('/pessoas/' + this.pessoa.cpf + '/'+ this.evento).then(response => {
                    this.pessoa = response.body;
                }, (error) => {
                    console.log("erro ao carregar pessoa" + error);
                });            
            },
            getValor: function(pessoa){
                if (!pessoa.alojamento || !pessoa.refeicao || !pessoa.nascimento)
                    return;

                this.$http.post('/valores/' + this.evento , pessoa).then(response => {
                    pessoa.valor = response.body;
                }, (error) => {
                    console.log("erro ao carregar pessoa" + error);
                });            
            },
            fazerIncricao: function(){
                console.log('Incrição feita :D');
            },
            getValorTotal: function(pessoa){
                var total = this.pessoa.valor;

                this.pessoa.dependentes.forEach(dependente => {
                    total += dependente.valor;
                });

                return this.formatPrice(total);
            },            
        }
    }
</script>
