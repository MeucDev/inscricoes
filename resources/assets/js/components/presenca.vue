<template>
<div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Alojamento</th>
                        <th>Valor</th>
                        <th>Refeição</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td><input type="checkbox" @change="calculaTotal" v-model="inscricao.presenca"></td>
                        <td>{{inscricao.pessoa.nome}}</td>
                        <td>{{inscricao.pessoa.idade}}</td>
                        <td>{{inscricao.alojamento}}</td>
                        <td>{{formatPrice(inscricao.valorAlojamento)}}</td>
                        <td>{{inscricao.refeicao}}</td>
                        <td>{{formatPrice(inscricao.valorRefeicao)}}</td>
                    </tr>
                    <tr v-for="dependente in inscricao.dependentes">
                        <td><input type="checkbox" @change="calculaTotal" v-model="dependente.presenca"></td>
                        <td>{{dependente.pessoa.nome}}</td>
                        <td>{{dependente.pessoa.idade}}</td>
                        <td>{{dependente.alojamento}}</td>
                        <td>{{formatPrice(dependente.valorAlojamento)}}</td>
                        <td>{{dependente.refeicao}}</td>
                        <td>{{formatPrice(dependente.valorRefeicao)}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th><h4>Inscrição<small>permitir edição do valor da inscrição</small></h4></th>
                        <td class="text-right"><h4><nobr>{{formatPrice(inscricao.valorInscricao)}}</nobr></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Total<small>total a pagar inscricção + camping</small></h4></th>
                        <td class="text-right"><h4><nobr>{{formatPrice(inscricao.valorTotal)}}</nobr></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Pago<small>Valor que já foi pago</small></h4></th>
                        <td class="text-right"><h4><nobr>{{formatPrice(inscricao.valorTotalPago)}}</nobr></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Restante</h4></th>
                        <td class="text-right"><h4><nobr>{{formatPrice(total)}}</nobr></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Recebido<small></small></h4></th>
                        <td class="text-right">
                            <div class="col-md-6">
                                <select v-model="inscricao.pessoa.equipeRefeicao" id="equipe" class="form-control input-lg">
                                    <option v-if="!equipeEhQuiosque" value="LAR_A">Lar A</option>
                                    <option v-if="!equipeEhQuiosque" value="LAR_B">Lar B</option>
                                    <option v-if="equipeEhQuiosque" value="QUIOSQUE_A">Quiosque A</option>
                                    <option v-if="equipeEhQuiosque" value="QUIOSQUE_B">Quiosque B</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input id="pago" class="form-control input-lg text-right" v-model="inscricao.recebido"/>
                            </div>
                        </td>
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
            this.initQz();
        },
        data (){
            return{
                inscricao : { 
                    pessoa: {nome: '', idade:''} 
                },
                total: 0.0
            }
        },
        methods:{
            equipeEhQuiosque: function(){
                return this.inscricao.equipeRefeicao.includes("QUIOSQUE");
            },
            getInscricao : function(id){
                this.inscricao = {pessoa: {nome: '', idade:''} };
                this.$http.get('/inscricoes/' + id).then(response => {
                    this.inscricao = response.body;
                    this.calculaTotal();
                    setTimeout(function(){
                        $("#pago").focus(function(){
                            $(this).select();
                        });
                        $("#pago").focus();
                    }, 100);
                }, (error) => {
                    this.showError(error);
                }); 
            },
            calculaTotal : function(){
                this.total = this.inscricao.valorTotal - this.inscricao.valorTotalPago;
                var desconto = 0.0;

                if (!this.inscricao.presenca)
                    desconto = Number(this.inscricao.valorRefeicao) + Number(this.inscricao.valorAlojamento);

                this.inscricao.dependentes.forEach(function(item) {
                    if (!item.presenca)
                        desconto += Number(item.valorTotal);
                });
                this.total = this.total - desconto;

                if (this.total < 0)
                    this.total = 0;
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
                    this.imprimir(this.inscricao.pessoa, function(){
                        window.location.reload();
                    });
                }, (error) => {
                    this.showError(error);
                });    
            },
        }
    }
</script>
