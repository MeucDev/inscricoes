<template>
<div class="box box-primary">
    <div class="person">
        <div class="box-header with-border">
            <span class="badge bg-light-blue">{{getTipo(pessoa.TIPO)}}</span>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" @click="remove(pessoa.id)">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="row box-body">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="parentesco">Parentesco</label>
                    <select name="parentesco" v-model="pessoa.TIPO" id="parentesco" class="form-control">
                        <option value="C">Cônjuge</option>
                        <option value="F">Filho(a)</option>
                        <option value="O">Outro</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" v-model="pessoa.nome"  class="form-control" id="nome">
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
                <div class="form-group">
                    <label for="datanascimento">Data de Nascimento</label>
                    <input type="text" v-model="pessoa.nascimento" @change="getvalor(pessoa, 'R')" class="form-control" id="datanascimento" placeholder="dd/mm/aaaa">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="alojamento">Hospedagem</label>
                    <select name="alojamento" id="alojamento" @change="getvalor(pessoa, 'R')" v-model="pessoa.alojamento" class="form-control">
                        <option value="CAMPING">Camping</option>
                        <option value="LAR">Lar Filadélfia</option>
                        <option value="OUTROS">Outro / Hotel na cidade</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="refeicao">Refeição</label>
                    <select name="refeicao" id="refeicao" @change="getvalor(pessoa, 'R')" v-model="pessoa.refeicao" class="form-control">
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
                    <h3>{{"Valor parcial: R$ " + formatPrice(pessoa.valor)}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>        
</template>

<script>
    import helpers from './helpers'

    export default {
        props: ['pessoa', 'remove', 'getvalor'],
        mixins: [helpers],
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            getTipo : function(tipo){
                switch(tipo) {
                    case 'C':
                        return 'Cônjuge';
                    case 'F':
                        return 'Filho';
                    default:
                        return 'Outro';
                }                
            },
        }
    }
</script>
