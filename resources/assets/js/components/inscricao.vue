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
                        <input type="text" v-bind:readonly="inscricao" v-validate="'required|digits:11'" v-model="pessoa.cpf" @change="getPessoa" class="form-control" id="cpf" name="cpf">
                        <span v-show="errors.has('cpf')" class="help-block">O CPF deve ter 11 dígitos</span>                        
                    </div>
                </div>
                <div class="col-md-3">
                    <div :class="{'form-group': true, 'has-error': errors.has('nome') }">
                        <label for="nome">Nome completo</label>
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
                        <input type="text" v-mask="'##/##/####'" v-validate="'required|date_format:dd/MM/yyyy'" class="form-control" @change="getValor(pessoa, evento, $event)" v-model="pessoa.nascimento" id="nascimento" name="nascimento" placeholder="dd/mm/aaaa">
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
                        <input type="text" v-mask="'## #########'" v-validate="{required:true, regex:/\d{2}\s\d{8,9}/}" v-model="pessoa.telefone" class="form-control" name="telefone" id="telefone" placeholder="47 999999999">
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
                    <input type="text" v-mask="'#####-###'" v-validate="{required:true, regex:/\d{5}-\d{3}/}"  v-model="pessoa.cep" class="form-control" id="cep" name="cep" placeholder="99999-999">
                    <span v-show="errors.has('cep')" class="help-block">O cep deve estar no formato 99999-999</span>                        
                </div>
            </div>
            <div class="col-md-1">
                <div :class="{'form-group': true, 'has-error': errors.has('uf') }">
                    <label for="uf">UF</label>
                    <input type="text" v-validate="'required|min:2|max:2'" v-model="pessoa.uf" class="form-control" id="uf" name="uf" style="text-transform:uppercase">
                    <span v-show="errors.has('uf')" class="help-block">O campo deve ter apenas duas letras ex: SC</span>                        
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
                    <select name="alojamento" v-validate="'required'" id="alojamento" @change="hospedagemChange(pessoa, $event);getValor(pessoa, evento, $event)" v-model="pessoa.alojamento" class="form-control">
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
                    <select name="refeicao" v-validate="'required'" id="refeicao" @change="refeicaoChange(pessoa);getValor(pessoa, evento, $event)" v-model="pessoa.refeicao" class="form-control">
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
                    <label >Valor parcial</label>
                    <input type="text" class="form-control text-right" :value="formatPrice(pessoa.valores.total)"  disabled="">
                </div>
            </div>
        </div>
    </div>    
	<dependente v-for="dependente in pessoa.dependentes" v-bind:key="dependente.id" 
        ref="dependentes"
        :pessoa="dependente" 
        :remove="removeDependente"
        :evento = "evento"
        >
    </dependente>
    <div class="text-right commands">
        <button type="button" class="btn btn-primary" @click="addDependente">Adicionar dependente</button>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">Valores</h4>
        </div>
        <div class="row box-body">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>{{pessoa.nome}}</th>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Camping</td>
                            <td class="text-right">{{formatPrice(pessoa.valores.alojamento)}}</td>
                        </tr>
                        <tr>
                            <td>Refeição</td>
                            <td class="text-right">{{formatPrice(pessoa.valores.refeicao)}}</td>
                        </tr>
                        <template v-for="dependente in pessoa.dependentes">
                            <tr>
                                <th>{{dependente.nome}}</th>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Camping</td>
                                <td class="text-right">{{formatPrice(dependente.valores.alojamento)}}</td>
                            </tr>
                            <tr>
                                <td>Refeição</td>
                                <td class="text-right">{{formatPrice(dependente.valores.refeicao)}}</td>
                            </tr>
                        </template>  
                        <tr>
                            <th><h4>Total inscrição <small v-if="pessoa.valores.desconto > 0"> (inscrição com desconto, será pago {{pessoa.valores.desconto|number}}% do valor da inscrição)</small></h4></th>
                            <td class="text-right"><h4><nobr>{{formatPrice(pessoa.valores.inscricao)}}</nobr></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Total camping</h4></th>
                            <td class="text-right"><h4><nobr>{{formatPrice(getTotalAlojamento())}}</nobr></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Total refeição</h4></th>
                            <td class="text-right"><h4><nobr>{{formatPrice(getTotalRefeicao())}}</nobr></h4></td>
                        </tr>
                        <tr v-if="getTotalDescontoEventoAnterior() > 0">
                            <th><h4>Crédito Congresso anterior</h4></th>
                            <td class="text-right"><h4><nobr>{{formatPrice(getTotalDescontoEventoAnterior())}}</nobr></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Total geral</h4></th>
                            <td class="text-right"><nobr>{{formatPrice(getTotalGeral() - getTotalDescontoEventoAnterior())}}</nobr></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-right commands">
                    <button type="button" id="confirmar" class="btn btn-success btn-lg" @click="fazerIncricao">Confirmar inscrição</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import VueTheMask from 'vue-the-mask';
    import swal from 'sweetalert2';
    import helpers from './helpers';
    import dependente from './dependente.vue';

    Vue.use(VeeValidate);    
    Vue.use(VueTheMask);
    
    const pessoaDefault = {
        id: -1, 
        TIPO: 'R', 
        cpf:'', 
        valores : {inscricao:0, refeicao : 0, alojamento: 0, total: 0},
        dependentes: []
    };

    export default {
        props: ['evento', 'inscricao', 'interno'],
        mixins: [helpers],
        components: {dependente},
        data (){
            return{
                pessoa : pessoaDefault
            }
        },
        mounted: function(){
            if (this.inscricao){
                this.getInscricao(this.inscricao);
            }
        },
        methods: {
            getInscricao : function(id){
                this.$http.get('/inscricoes/' + id + '/pessoa').then(response => {
                    this.pessoa = response.body;
                }, (error) => {
                    this.showError(error);
                }); 
            },            
            addDependente: function(){
                if (!this.interno && this.pessoa.dependentes.length == 6)
                {
                    swal(
                        'Opa, sua família é bem grande',
                        'Caso deseja adicionar mais dependentes, entre em contato com a organização!',
                        'warning'
                    );
                    return;
                }
                this.pessoa.dependentes.push({
                    id: this.pessoa.dependentes.length -100,
                    alojamento: this.pessoa.alojamento,
                    refeicao: this.pessoa.refeicao,
                    valores : {inscricao:0, refeicao : 0, alojamento: 0, total: 0},
                });
            },
            removeDependente: function(id){
                this.pessoa.dependentes = this.pessoa.dependentes.filter(function( obj ) {
                    return obj.id !== id;
                })
            },
            getPessoa: function(){
                if (!this.pessoa.cpf)
                    return;
                    
                this.$http.get('/pessoas/' + this.pessoa.cpf + '/'+ this.evento).then(response => {
                    this.pessoa = response.body;

                    if(this.pessoa.bloqueado) {
                        swal(
                            'Aguarde mais um pouco',
                            'Neste momento as inscrições estão abertas para aqueles que solicitaram reserva de vaga para o evento de 2022 em decorrência do cancelamento do Congresso em 2020. Em breve chegará sua vez!',
                            'success'
                        ).then((result) =>{
                            $("#confirmar").hide();
                        }); 
                    } else {
                        if (this.pessoa.inscricao)
                        this.inscricao = this.pessoa.inscricao;

                        this.ajustarTodasRefeicoes(this.pessoa);

                        $("#confirmar").show();
                        
                        if (this.pessoa.inscricaoPaga){
                            swal(
                                'Já está tudo certo',
                                'Identificamos em nosso sistema que sua inscrição já foi feita e está paga. Nos encontramos no dia do evento!',
                                'success'
                            ).then((result) =>{
                                //$("#confirmar").hide();
                            });                   
                        }else if(this.pessoa.pagseguroLink){
                            swal({
                                title: 'Só falta pagar',
                                text: "Identificamos em nosso sistema que sua inscrição não está paga. Se deseja fazer o pagamento clique em Pagar. Caso você queira fazer alguma alteração clique em cancelar e refaça a sua inscrição",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Pagar'
                            }).then((result) =>{
                                if (result.value){
                                    window.location.replace(this.pessoa.pagseguroLink);
                                }
                            });                         
                        }
                    }
                    
                }, (error) => {
                    console.log(error.body);
                });            
            },
            criarIncricao: function(){
                swal({
                    title: 'Processando!',
                    allowOutsideClick: false,
                    text: 'Estamos ajeitando tudo para que você tenha um ótimo congresso.',
                    onOpen: () => {
                        swal.showLoading()
                    }
                });

                this.pessoa.interno = this.interno;

                var self = this;
                this.$http.post('/inscricoes/criar/' + this.evento , self.pessoa).then(response => {
                    var inscricaoCriada = response.body;
                    swal.close();
                    if (self.interno){
                        $('#modal').modal('hide');
                    }
                    else{
                        if(this.getTotalDescontoEventoAnterior() >= this.getTotalPagar()) {
                            swal({
                                allowOutsideClick: false,
                                title: 'Que boa notícia!',
                                text: 'O valor de crédito existente em seu nome cobre todo o montante para o evento atual, portanto nada mais precisa ser pago. Nos vemos lá!',
                                type: 'success'
                            }).then(() => {
                                this.setInscricaoPagaCreditosEventoAnterior(inscricaoCriada.inscricao_id);
                            });
                        } else {
                            var mensagemAdicional = '';
                            if(this.getTotalPagar() < this.getTotalGeral()) {
                                mensagemAdicional = 'O restante acertaremos no dia do evento. '
                            }
                            var valorDescontoEventoAnterior = '';
                            if(this.getTotalDescontoEventoAnterior() > 0) {
                                valorDescontoEventoAnterior = ` com ${this.formatPrice(this.getTotalDescontoEventoAnterior())} de desconto por crédito do Congresso anterior. `
                            }
                            swal({
                                allowOutsideClick: false,
                                title: 'Estamos quase lá!',
                                text: `Ao clicar em OK você será redirecionado para o pagamento da inscrição (${this.formatPrice(this.getTotalPagar())}${valorDescontoEventoAnterior}). ${mensagemAdicional}Até lá!`,
                                type: 'success'
                            }).then((result) => {
                                if (inscricaoCriada.link) {
                                    window.location.replace(inscricaoCriada.link);
                                }
                            });
                        }
                    }
                }, (error) => {
                    this.showError(error);
                });            
            },
            alterarIncricao: function(){
                swal({
                    title: 'Processando!',
                    allowOutsideClick: false,
                    text: 'Atualizando dados.',
                    onOpen: () => {
                        swal.showLoading()
                    }
                });

                this.$http.put('/inscricoes/' + this.inscricao, this.pessoa).then(response => {
                    swal.close();
                    $("#modal").modal("hide");
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

                        if (self.inscricao)
                            self.alterarIncricao();
                        else
                            self.criarIncricao();
                    });
                });
            },

            getTotalPagar: function(){
                var total = this.pessoa.valores.boleto;

                if (this.pessoa.dependentes){
                    this.pessoa.dependentes.forEach(dependente => {
                        total += dependente.valores.boleto;
                    });
                }
                
                return total;
            },
            getTotalRefeicao: function(){
                var total = this.pessoa.valores.refeicao;

                if (this.pessoa.dependentes){
                    this.pessoa.dependentes.forEach(dependente => {
                        total += dependente.valores.refeicao;
                    });
                }

                return total;
            },
            getTotalAlojamento: function(){
                var total = this.pessoa.valores.alojamento;

                if (this.pessoa.dependentes){
                    this.pessoa.dependentes.forEach(dependente => {
                        total += dependente.valores.alojamento;
                    });
                }

                return total;
            },
            getTotalGeral: function(){
                var total = this.pessoa.valores.total;

                if (this.pessoa.dependentes){
                    this.pessoa.dependentes.forEach(dependente => {
                        total += dependente.valores.total;
                    });
                }

                return total;
            },
            getTotalDescontoEventoAnterior: function(){
                return this.pessoa.valores.descontoEventoAnterior;
            },
            setInscricaoPagaCreditosEventoAnterior: function(inscricao_id){
                this.$http.put('/inscricoes/set-pago/' + inscricao_id).then(response => {
                    swal.close();
                    $("#modal").modal("hide");
                }, (error) => {
                    this.showError(error);
                });     
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