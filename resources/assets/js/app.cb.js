
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap.cb');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import inscricao from './components/inscricao.vue';
import presenca from './components/presenca.vue';
import cracha from './components/cracha.vue';
import equipe from './components/equipe.vue';

window.modalApp = new Vue({
    el: '#modalApp',
    data () {
        return {
            title:'',
            props: {},
            componentName:''
        }
    },
    mounted: function(){
        $('#modal').on('hidden.bs.modal', this.clear);
    },
    components: { inscricao, presenca, cracha, equipe },
    methods:{
        clear: function(){
            this.title = '';
            this.componentName = '';
            this.props = {};
        },
        show: function(title, componentName, props){
            this.title = title;
            this.componentName = componentName;
            this.props = props;
            $("#modal").modal();
        },
    }
});
