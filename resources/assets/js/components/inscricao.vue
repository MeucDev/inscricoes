<template>
<div id="inscricao">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">Dados do responsável</h4>
        </div>
        <div class="person">
            <div class="row box-body">
                <div class="col-md-3">
                    <div :class="{'form-group': true, 'has-error': errors.has('cpf') }">
                        <label for="cpf">CPF</label>
                        <input type="text" v-validate="'required|digits:11'" v-model="pessoa.cpf" @change="getPessoa" class="form-control" id="cpf" name="cpf">
                        <span v-show="errors.has('cpf')" class="help-block">O CPF deve ter 11 dígitos</span>                        
                    </div>
                </div>
                <div class="col-md-3">
                    <div :class="{'form-group': true, 'has-error': errors.has('nome') }">
                        <label for="nome">Nome</label>
                        <input v-validate="'required'" type="text" v-model="pessoa.nome" class="form-control" name="nome" id="nome">
                        <span v-show="errors.has('nome')" class="help-block">Campo obrigatório</span>                        
                    </div>
                </div>
                <div class="col-md-3">
                    <div :class="{'form-group': true, 'has-error': errors.has('nomecracha') }">
                        <label for="nomecracha">Nome cracha</label>
                        <input v-validate="'required'" type="text" v-model="pessoa.nomecracha" class="form-control" name="nomecracha" id="nomecracha">
                        <span v-show="errors.has('nomecracha')" class="help-block">Campo obrigatório</span>                        
                    </div>
                </div>                
                <div class="col-md-3">
                    <div :class="{'form-group': true, 'has-error': errors.has('email') }">
                        <label for="email">Email</label>
                        <input v-validate="'required|email'" v-model="pessoa.email" name="email" id="email" type="text" class="form-control">
                        <span v-show="errors.has('email')" class="help-block">O e-mail deve ser válido</span>                        
                    </div>
                </div>
            </div>
            <div class="row box-body">
                <div class="col-md-4">
                    <div :class="{'form-group': true, 'has-error': errors.has('nascimento') }">
                        <label for="nascimento">Data de Nascimento</label>
                        <input type="text" v-validate="'required|date_format:DD/MM/YYYY'" class="form-control" @change="getValor(pessoa, 'R')" v-model="pessoa.nascimento" id="nascimento" name="nascimento" placeholder="dd/mm/aaaa">
                        <span v-show="errors.has('nascimento')" class="help-block">A data deve estar no formato dd/mm/aaaa</span>                        
                    </div>
                </div>
                <div class="col-md-4">
                    <div :class="{'form-group': true, 'has-error': errors.has('sexo') }">
                        <label for="sexo">Sexo</label>
                        <select v-validate="'required'" v-model="pessoa.sexo" id="sexo" class="form-control" name="sexo">
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                        </select>
                        <span v-show="errors.has('sexo')" class="help-block">Campo obrigatório</span>                        
                    </div>
                </div>
                <div class="col-md-4">
                    <div :class="{'form-group': true, 'has-error': errors.has('telefone') }">
                        <label for="telefone">Telefone</label>
                        <input type="text" v-validate="{required:true, regex:/\d{2}\s\d{8,9}/}" v-model="pessoa.telefone" class="form-control" name="telefone" id="telefone" placeholder="47 999999999">
                        <span v-show="errors.has('telefone')" class="help-block">O telefone deve estar no formato 47 999999999</span>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h4 class="box-title">Endereço</h4>
        </div>
        <div class="row box-body">
            <div class="col-md-3">
                <div :class="{'form-group': true, 'has-error': errors.has('cep') }">
                    <label for="cep">CEP</label>
                    <input type="text" v-validate="{required:true, regex:/\d{5}-\d{3}/}"  v-model="pessoa.cep" class="form-control" id="cep" name="cep" placeholder="99999-999">
                    <span v-show="errors.has('cep')" class="help-block">O cep deve estar no formato 99999-999</span>                        
                </div>
            </div>
            <div class="col-md-1">
                <div :class="{'form-group': true, 'has-error': errors.has('uf') }">
                    <label for="uf">UF</label>
                    <input type="text" v-validate="'required|min:2|max:2'" v-model="pessoa.uf" class="form-control" id="uf" name="uf" style="text-transform:uppercase">
                    <span v-show="errors.has('uf')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-4">
                <div :class="{'form-group': true, 'has-error': errors.has('cidade') }">
                    <label for="cidade">Cidade</label>
                    <input type="text" v-validate="'required'" v-model="pessoa.cidade" class="form-control" id="cidade" name="cidade">
                    <span v-show="errors.has('cidade')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-4">
                <div :class="{'form-group': true, 'has-error': errors.has('bairro') }">
                    <label for="bairro">Bairro</label>
                    <input type="text" v-validate="'required'" v-model="pessoa.bairro" class="form-control" id="bairro" name="bairro">
                    <span v-show="errors.has('bairro')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
        </div>
        <div class="row box-body">
            <div class="col-md-10">
                <div :class="{'form-group': true, 'has-error': errors.has('endereco') }">
                    <label for="endereco">Endereço</label>
                    <input type="text" v-validate="'required'" v-model="pessoa.endereco" class="form-control" id="endereco" name="endereco">
                    <span v-show="errors.has('endereco')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-2">
                <div :class="{'form-group': true, 'has-error': errors.has('nroend') }">
                    <label for="nroend">Número</label>
                    <input type="text" v-validate="'required'" v-model="pessoa.nroend" class="form-control" id="nroend" name="nroend">
                    <span v-show="errors.has('nroend')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
        </div>  
        <div class="box-header with-border">
            <h4 class="box-title">Opções</h4>
        </div>
        <div class="row box-body">
            <div class="col-md-4">
                <div :class="{'form-group': true, 'has-error': errors.has('alojamento') }">
                    <label for="alojamento">Hospedagem</label>
                    <select name="alojamento" v-validate="'required'" id="alojamento" @change="getValor(pessoa, 'R')" v-model="pessoa.alojamento" class="form-control">
                        <option value="CAMPING">Camping</option>
                        <option value="LAR">Lar Filadélfia (Tratar direto)</option>
                        <option value="OUTROS">Outro / Hotel na cidade</option>
                    </select>
                    <span v-show="errors.has('alojamento')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-4">
                <div :class="{'form-group': true, 'has-error': errors.has('refeicao') }">
                    <label for="refeicao">Refeição</label>
                    <select name="refeicao" v-validate="'required'" id="refeicao" @change="getValor(pessoa, 'R')" v-model="pessoa.refeicao" class="form-control">
                        <option value="QUIOSQUE_COM_CAFE">Quiosque com café</option>
                        <option value="QUIOSQUE_SEM_CAFE">Quiosque sem café</option>
                        <option value="LAR_COM_CAFE">Lar Filadélfia com café</option>
                        <option value="LAR_SEM_CAFE">Lar Filadélfia sem café</option>
                        <option value="NENHUMA">Nenhuma</option>
                    </select>
                    <span v-show="errors.has('refeicao')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group text-right">
                    <label for="refeicao">Valor parcial</label>
                    <input type="text" class="form-control text-right" :value="'R$ ' + formatPrice(pessoa.valor)"  disabled="">
                </div>
            </div>
        </div>
    </div>    
	<dependente v-for="dependente in pessoa.dependentes" v-bind:key="dependente.id" 
        ref="dependentes"
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
    import swal from 'sweetalert2';
    import helpers from './helpers';
    import dependente from './dependente.vue';

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
                pessoa : {id: -1, TIPO: 'R', cpf:'', valor : 0, valorTotal : 0, dependentes: []}
            }
        },
        methods: {
            addDependente: function(){
                this.pessoa.dependentes.push({
                    id: this.pessoa.dependentes.length -100,
                    alojamento: this.pessoa.alojamento,
                    refeicao: this.pessoa.refeicao,
                    valor: 0
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
                    this.showError(error);
                });            
            },
            getValor: function(pessoa){
                if (!pessoa.alojamento || !pessoa.refeicao || !pessoa.nascimento)
                    return;

                this.$http.post('/valores/' + this.evento , pessoa).then(response => {
                    pessoa.valor = response.body;
                }, (error) => {
                    this.showError(error);
                });            
            },
            postIncricao: function(){

                swal({
                    title: 'Processando!',
                    text: 'Estamos ajeitando tudo para que você tenha um ótimo congresso.',
                    onOpen: () => {
                        swal.showLoading()
                    }
                });

                this.$http.post('/eventos/' + this.evento + '/inscricao' , this.pessoa).then(response => {
                    var pagseguro = response.body;
                    swal.close();
                    swal(
                        'Estamos quase lá!',
                        'Ao clicar OK você será redirecionado para a página de pagamento!',
                        'success'
                    ).then((result) => {
                        window.location.replace(pagseguro.link);
                    });
                }, (error) => {
                    this.showError(error);
                });            
            },
            fazerIncricao: function(){               
                var promises = [];
                if (this.$refs.dependentes){
                    this.$refs.dependentes.forEach(function(dependente) {
                        var promise = dependente.validateAll().then(dependente, (result) => {
                            return result;
                        });
                        promises.push(promise);
                    });                
                }

                var self = this;
                Promise.all(promises).then(function(values) {
                    self.$validator.validateAll().then((result) => {

                        var valido = !values.some(function(item){ return item == false; });

                        if (!valido){
                            self.showError("Existem dados incorretos no cadatro dos dependentes!");
                            return;
                        }
                        
                        if (!result){
                            self.showError("Existem dados incorretos no cadastro do responsável!");
                            return;
                        }

                        self.postIncricao();
                    });
                });
            },
            getValorTotal: function(pessoa){
                var total = this.pessoa.valor;

                this.pessoa.dependentes.forEach(dependente => {
                    total += dependente.valor;
                });

                return this.formatPrice(total);
            },
            showError: function(error){
                var message;
                if (typeof error == "string")
                    message = error;
                else
                {
                    message = error.body.message;
                    console.log(error.body);
                }

                swal('Oops...', message, 'error');
            }
        }
    }
</script>
