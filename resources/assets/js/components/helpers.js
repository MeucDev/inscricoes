
// define a mixin object
export default {
    methods: {
        formatPrice : function(value) {
            var val = (value/1).toFixed(2).replace('.', ',');
            var price = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            return 'R$ ' + price;
        },    
        hospedagemChange: function(pessoa){
            var focused = $(':focus');
            var box = focused.closest(".box");
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

