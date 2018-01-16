
// define a mixin object
export default {
    methods: {
        formatPrice : function(value) {
            let val = (value/1).toFixed(2).replace('.', ',');
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },    
    }
}

