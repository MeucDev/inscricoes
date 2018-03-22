<template>
<div>
    <div class="row">
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
                        <td><input type="checkbox" @change="calculaTotal" v-model="inscricao.presenca"></td>
                        <td>{{inscricao.pessoa.nome}}</td>
                        <td>{{inscricao.pessoa.idade}}</td>
                        <td>{{inscricao.alojamento}}</td>
                        <td>{{inscricao.valorAlojamento}}</td>
                        <td>{{inscricao.refeicao}}</td>
                        <td>{{inscricao.valorRefeicao}}</td>
                    </tr>
                    <tr v-for="dependente in inscricao.dependentes">
                        <td><input type="checkbox" @change="calculaTotal" v-model="dependente.presenca"></td>
                        <td>{{dependente.pessoa.nome}}</td>
                        <td>{{dependente.pessoa.idade}}</td>
                        <td>{{dependente.alojamento}}</td>
                        <td>{{dependente.valorAlojamento}}</td>
                        <td>{{dependente.refeicao}}</td>
                        <td>{{dependente.valorRefeicao}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th><h4>Total<small> (valor total da inscrição)</small></h4></th>
                        <td class="text-right"><h4><nobr>{{inscricao.valorTotal}}</nobr></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Restante</h4></th>
                        <td class="text-right"><h4><nobr>R$ {{total}}</nobr></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Pago<small></small></h4></th>
                        <td class="text-right"><input id="pago" class="form-control input-lg text-right" v-model="inscricao.valorTotalPago"/></td>
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

    export default {
        props: ['id'],
        mounted: function(){
            this.getInscricao(this.id);
        },
        watch: { 
      	    id: function(newVal, oldVal) {
                this.getInscricao(newVal);
            }
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
            confirmar : function(){
                this.$http.post('/inscricoes/' + this.id + "/presenca", this.inscricao).then(response => {
                    window.location.reload();
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
