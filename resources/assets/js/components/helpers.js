
// define a mixin object
export default {
    methods: {
        formatPrice : function(value) {
            var val = (value/1).toFixed(2).replace('.', ',');
            var price = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            return 'R$ ' + price;
        },    
        ajustarTodasRefeicoes: function(pessoa){
            if (pessoa.alojamento == "LAR")
                pessoa.refeicao = "LAR";

            pessoa.dependentes.forEach(dependente => {
                if (dependente.alojamento == "LAR")
                    dependente.refeicao = "LAR";
            });

            $('select[id=refeicao]').each(function(index){
                if ($(this).val() == "LAR")
                    $(this).prop("disabled", true);
                console.log($(this).val());
            });
        },
        refeicaoChange: function(event){
            alert('oi');
            $(event.target).find("option[value='LAR']").each(function() {
                $(this).remove();
            });
        },
        hospedagemChange: function(pessoa, event){
            var box = $(event.target).closest(".box");
            var refeicao = box.find("#refeicao");

            refeicao.find("option[value='LAR']").each(function() {
                $(this).remove();
            });

            if (pessoa.alojamento == "LAR"){
                refeicao.prop("disabled", true);
                refeicao.append(new Option('Lar Filadélfia (Tratar direto)', 'LAR', false, false));
                pessoa.refeicao = "LAR";
            }else{
                refeicao.prop("disabled", false);
            }
        },
    }
}

