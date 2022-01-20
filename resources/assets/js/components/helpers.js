
import Vue from 'vue';
import swal from 'sweetalert2';
import price from './price';

// define a mixin object
export default {
    mixins: [price],
    methods: {
        ajustarTodasRefeicoes: function(pessoa){
            if (pessoa.alojamento == "LAR")
                pessoa.refeicao = "LAR";

            pessoa.dependentes.forEach(dependente => {
                if (dependente.alojamento == "LAR")
                    dependente.refeicao = "LAR";
            });

            setTimeout(function(){
                $('select[id=refeicao]').each(function(index){
                    if ($(this).val() == "LAR")
                        $(this).prop("disabled", true);
                });
            }, 100);
        },
        hospedagemChange: function(pessoa, event){
            var box = $(event.target).closest(".box");
            var refeicao = box.find("#refeicao");

            if (pessoa.alojamento == "LAR"){
                //refeicao.prop("disabled", true);
                pessoa.refeicao = "LAR_SEM_CAFE";
            }else{
                if (pessoa.refeicao = "LAR")
                    pessoa.refeicao = "";
                refeicao.prop("disabled", false);
            }
        },
        refeicaoChange: function(pessoa){
            if (pessoa.refeicao == "LAR" && pessoa.alojamento != "LAR"){
                pessoa.refeicao = "";
                swal(
                    'Opção inválida',
                    'Essa opção é válida apenas se a hospedagem for no Lar Filadélfia.',
                    'warning'
                );                
            }
        },
        getValor: function(pessoa, evento, event){
            if (!pessoa.nascimento){
                var el =  $(event.target);
                swal(
                    'Informação',
                    'Informe a data de nascimento para obter o valor!',
                    'info'
                ).then((result) =>{
                    var box = el.closest(".box");
                    box.find("#nascimento").focus();
                });                   
                return;
            }

            if (pessoa.nascimento && (pessoa.alojamento || pessoa.refeicao)){
                this.$http.post('/valores/' + evento , pessoa).then(response => {
                    pessoa.valores = response.body;
                }, (error) => {
                    console.log(error);
                });            
            }
        },
    }
}

