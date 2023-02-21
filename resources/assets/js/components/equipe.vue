<template>
<div class="box box-primary">
    <div class="row box-header">
        <h3>Evento: {{equipe.evento}}</h3>
        <h3>Equipe: {{equipe.nome}}</h3>
    </div>
    <div class="row box-body">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width: 10%">Imprimir</th>
                        <th>Nome</th>
                    </tr>
                    <tr v-for="membro in equipe.membros">
                        <td><input type="checkbox" v-model="membro.imprimir"></td>
                        <td>{{membro.nome}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="text-right commands">
                <button class="btn btn-success btn-lg" @click="imprimir">Imprimir</button>
            </div>
        </div>
    </div>
</div>    
</template>

<script>
    import cracha from './cracha';   
    import jsPDF from 'jspdf';
    import swal from 'sweetalert2';

    export default {
        props: ['id', 'equipe'],
        mixins: [cracha],
        data (){
            return{
                equipe :  {
                    id: 1,
                    evento: 'XXIII Congresso de Famílias',
                    nome: 'Equipe',
                    membros: [
                    ]
                }
            }
        },
        mounted: function(){
            if (this.id){
                this.getEquipe(this.id);
            }
        },
        methods:{
            getEquipe: function(id) {
                this.$http.get('/equipes/imprimir/' + id ).then(response => {
                    this.equipe = response.body;
                }, (error) => {
                    this.showError(error);
                }); 
            },
            imprimir: function() {
                this.imprimirEquipe(this.equipe);     
            }
        }
    }
</script>