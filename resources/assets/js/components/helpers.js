
import Vue from 'vue';
import swal from 'sweetalert2';
import price from './price';

// define a mixin object
export default {
    mixins: [price],
    methods: {
        ajustarTodasRefeicoes: function(pessoa){
            if (pessoa.alojamento == "LAR")
                pessoa.refeicao = "NENHUMA";

            pessoa.dependentes.forEach(dependente => {
                if (dependente.alojamento == "LAR")
                    dependente.refeicao = "NENHUMA";
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
                pessoa.refeicao = "NENHUMA";
                refeicao.prop("disabled", true);
            }else{
                //if (pessoa.refeicao == "LAR")
                //     pessoa.refeicao = "";
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
                // Só mostrar alerta se houver um evento real (não quando chamado automaticamente)
                if (event && event.target && $(event.target).length > 0) {
                    var el = $(event.target);
                    swal(
                        'Informação',
                        'Informe a data de nascimento para obter o valor!',
                        'info'
                    ).then((result) =>{
                        var box = el.closest(".box");
                        box.find("#nascimento").focus();
                    });
                }
                return;
            }

            if (pessoa.nascimento && (pessoa.alojamento || pessoa.refeicao)){
                var eventoId = typeof evento === 'object' ? evento.id : evento;
                this.$http.post('/valores/' + eventoId , pessoa).then(response => {
                    pessoa.valores = response.body;
                }, (error) => {
                    console.log('Erro ao calcular valores:', error);
                });            
            }
        },
    }
}

