<template>
<div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>Presença</th>
                        <th>Imprimir</th>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Refeição</th>
                        <th>Valor</th>
                        <th>Hospedagem</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td><input type="checkbox" @change="calculaTotal" v-model="inscricao.presenca"></td>
                        <td><input type="checkbox" v-model="inscricao.imprimir"></td>
                        <td>{{inscricao.pessoa.nome}}</td>
                        <td>{{inscricao.pessoa.idade}}</td>
                        <td>{{inscricao.refeicao}}</td>
                        <td>{{formatPrice(inscricao.valorRefeicao)}}</td>
                        <td>{{inscricao.alojamento}}</td>
                        <td>{{formatPrice(inscricao.valorAlojamento)}}</td>
                    </tr>
                    <tr v-for="dependente in inscricao.dependentes">
                        <td><input type="checkbox" @change="calculaTotal" v-model="dependente.presenca"></td>
                        <td><input type="checkbox" v-model="dependente.imprimir"></td>
                        <td>{{dependente.pessoa.nome}}</td>
                        <td>{{dependente.pessoa.idade}}</td>
                        <td>{{dependente.refeicao}}</td>
                        <td>{{formatPrice(dependente.valorRefeicao)}}</td>
                        <td>{{dependente.alojamento}}</td>
                        <td>{{formatPrice(dependente.valorAlojamento)}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <tr v-if="inscricao.inscricaoPaga == 0">
                        <td colspan="2">
                            <div class="callout callout-danger">
                                <h4>A inscrição não está paga</h4>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="inscricao.inscricaoPaga == 0">
                        <th><h4>Inscrição <small>valor da inscrição</small></h4></th>
                        <td class="text-right">
                            <input class="form-control input-lg text-right" @change="calculaTotal" v-model="inscricao.valorInscricao"/>
                        </td>
                    </tr>
                    <tr v-if="inscricao.inscricaoPaga == 1">
                        <td colspan="2">
                            <div class="callout callout-success">
                                <h4>A inscrição já está paga</h4>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="inscricao.presencaConfirmada == 1">
                        <td colspan="2">
                            <div class="callout callout-success">
                                <h4>A presença já foi confirmada</h4>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="inscricao.equipeRefeicao">
                        <th><h4>Equipe <small>para as refeições</small></h4></th>
                        <td class="text-right">
                            <select v-model="inscricao.equipeRefeicao" id="equipe" class="form-control input-lg">
                                <option v-if="!equipeEhQuiosque()" value="LAR_A">Lar A</option>
                                <option v-if="!equipeEhQuiosque()" value="LAR_B">Lar B</option>
                                <option v-if="equipeEhQuiosque()" value="QUIOSQUE_A">Quiosque A</option>
                                <option v-if="equipeEhQuiosque()" value="QUIOSQUE_B">Quiosque B</option>
                            </select>
                        </td>
                    </tr>
                    <tr v-if="inscricao.presencaConfirmada == 0">
                        <th><h1 class="red">Total a pagar<small> inscrição + camping</small></h1></th>
                        <td class="text-right"><h1><nobr>{{formatPrice(total)}}</nobr></h1></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="text-right commands">
                <button class="btn btn-success btn-lg" @click="confirmar">Confirmar</button>
            </div>
        </div>
    </div>
</div>    
</template>

<script>
    import Vue from 'vue';
    import swal from 'sweetalert2';
    import price from './price'; 
    import cracha from './cracha';   

    export default {
        props: ['id'],
        mixins: [price, cracha],
        mounted: function(){
            this.getInscricao(this.id);
        },
        data (){
            return{
                inscricao : { 
                    pessoa: {nome: '', idade:''} 
                },
                total: 0
            }
        },
        methods:{
            equipeEhQuiosque: function(){
                var refeicao = this.inscricao.refeicao;
                if (refeicao)
                    return refeicao.startsWith("QUIOSQUE");
            },
            getInscricao : function(id){
                this.inscricao = {pessoa: {nome: '', idade:''} };
                this.$http.get('/inscricoes/' + id).then(response => {
                    this.inscricao = response.body;
                    this.calculaTotal();
                }, (error) => {
                    this.showError(error);
                }); 
            },
            calculaTotalInscricao(inscricao){
                var valor = Number(inscricao.valorInscricao) - Number(inscricao.valorInscricaoPago);

                if (!inscricao.presenca)
                    return valor;
                
                valor = valor + Number(inscricao.valorAlojamento);

                return valor;
            },
            calculaTotal : function(){
                this.total = this.calculaTotalInscricao(this.inscricao);

                var self = this;
                this.inscricao.dependentes.forEach(function(item) {
                    self.total += self.calculaTotalInscricao(item);
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
            },
            confirmar : function(){
                this.$http.post('/inscricoes/' + this.id + "/presenca", this.inscricao).then(response => {
                    this.imprimir(this.inscricao);
                    $('#modal').modal('hide');
                }, (error) => {
                    this.showError(error);
                });    
            },
        }
    }
</script>
