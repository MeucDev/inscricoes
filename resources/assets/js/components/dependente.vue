<template>
<div class="box box-primary">
    <div class="person">
        <div class="box-header with-border">
            <span class="badge bg-light-blue">2</span>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="row box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="parentesco">Parentesco</label>
                    <select name="parentesco" id="parentesco" class="form-control">
                        <option value="0">Cônjuge</option>
                        <option value="1">Filho(a)</option>
                        <option value="2">Pai/Mãe</option>
                        <option value="3">Outro</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="datanascimento">Data de Nascimento</label>
                    <input type="text" class="form-control" id="datanascimento" placeholder="dd/mm/aaaa">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="hospedagem">Hospedagem</label>
                    <select name="hospedagem" id="hospedagem" class="form-control">
                        <option value="0">Camping</option>
                        <option value="1">Lar Filadélfia</option>
                        <option value="2">Outro / Hotel na cidade</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="alimentacao">Alimentação</label>
                    <select name="alimentacao" id="alimentacao" class="form-control">
                        <option value="0">Quiosque</option>
                        <option value="1">Lar Filadélfia</option>
                        <option value="2">Outro / Não necessito</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>        
</template>

<script>
    export default {
        props: ['message'],
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
