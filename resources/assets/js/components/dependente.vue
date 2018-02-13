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
            <div class="col-md-2">
                <div :class="{'form-group': true, 'has-error': errors.has('parentesco') }">
                    <label for="parentesco">Parentesco</label>
                    <select name="parentesco" v-validate="'required'" v-model="pessoa.TIPO" id="parentesco" class="form-control">
                        <option value="C">Cônjuge</option>
                        <option value="F">Filho(a)</option>
                        <option value="O">Outro</option>
                    </select>
                    <span v-show="errors.has('parentesco')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-4">
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
                <div :class="{'form-group': true, 'has-error': errors.has('nascimento') }">
                    <label for="nascimento">Data de Nascimento</label>
                    <input type="text" v-mask="'##/##/####'" v-validate="'required|date_format:DD/MM/YYYY'" class="form-control" @change="getValor(pessoa)" v-model="pessoa.nascimento" id="nascimento" name="nascimento" placeholder="dd/mm/aaaa">
                    <span v-show="errors.has('nascimento')" class="help-block">A data deve estar no formato dd/mm/aaaa</span>                        
                </div>
            </div>
        </div>
        <div class="row box-body">
            <div class="col-md-2">
                <div :class="{'form-group': true, 'has-error': errors.has('sexo') }">
                    <label for="sexo">Sexo</label>
                    <select v-validate="'required'" v-model="pessoa.sexo" id="sexo" class="form-control" name="sexo">
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                    </select>
                    <span v-show="errors.has('sexo')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-3">
                <div :class="{'form-group': true, 'has-error': errors.has('alojamento') }">
                    <label for="alojamento">Hospedagem</label>
                    <select name="alojamento" v-validate="'required'" id="alojamento" @change="hospedagemChange(pessoa, $event);getValor(pessoa)" v-model="pessoa.alojamento" class="form-control">
                        <option value="CAMPING">Camping</option>
                        <option value="LAR">Lar Filadélfia (Tratar direto)</option>
                        <option value="OUTROS">Outro / Hotel na cidade</option>
                    </select>
                    <span v-show="errors.has('alojamento')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-3">
                <div :class="{'form-group': true, 'has-error': errors.has('refeicao') }">
                    <label for="refeicao">Refeição</label>
                    <select name="refeicao" v-validate="'required'" id="refeicao" @change="refeicaoChange($event);getValor(pessoa)" v-model="pessoa.refeicao" class="form-control">
                        <option value="QUIOSQUE_COM_CAFE">Quiosque com café</option>
                        <option value="QUIOSQUE_SEM_CAFE">Quiosque sem café</option>
                        <option value="LAR_COM_CAFE">Lar Filadélfia com café</option>
                        <option value="LAR_SEM_CAFE">Lar Filadélfia sem café</option>
                        <option value="LAR">Lar Filadélfia (Tratar direto)</option>
                        <option value="NENHUMA">Nenhuma</option>
                    </select>
                    <span v-show="errors.has('refeicao')" class="help-block">Campo obrigatório</span>                        
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group text-right">
                    <label for="refeicao">Valor parcial</label>
                    <input type="text" class="form-control text-right" :value="formatPrice(pessoa.valores.total)"  disabled="">
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
    import helpers from './helpers';

    Vue.use(VueTheMask)

    export default {
        props: ['pessoa', 'remove', 'getValor'],
        mixins: [helpers],
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
            validateAll: function(){
                return this.$validator.validateAll();
            },
        }
    }
</script>
