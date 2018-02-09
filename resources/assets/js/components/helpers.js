
// define a mixin object
export default {
    methods: {
        formatPrice : function(value) {
            var val = (value/1).toFixed(2).replace('.', ',');
            var price = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            return 'R$ ' + price;
        },    
    }
}

