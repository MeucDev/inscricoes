<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	
	class AdminPessoasController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "Nome";
			$this->limit = "20";
			$this->orderby = "nome,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = true;
			$this->button_export = true;
			$this->table = "pessoas";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Nome","name"=>"nome"];
			$this->col[] = ["label"=>"CPF","name"=>"cpf"];
			$this->col[] = ["label"=>"E-mail","name"=>"email"];
			$this->col[] = ["label"=>"Telefone","name"=>"telefone"];
			$this->col[] = ["label"=>"Idade","name"=>"idade"];
			$this->col[] = ["label"=>"Cidade","name"=>"cidade"];
			$this->col[] = ["label"=>"Tipo","name"=>"TIPO","callback_php"=>'$this->getTipo($row->TIPO)'];
			$this->col[] = ["label"=>"Responsável","name"=>"responsavel_id","join"=>"pessoas,nome"];
			$this->col[] = ["label"=>"Inativo","name"=>"inativo"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Cpf','name'=>'cpf','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Nome','name'=>'nome','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Nome cracha','name'=>'nomecracha','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Nascimento','name'=>'nascimento','type'=>'datetime','validation'=>'required','readonly' =>true,'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Casamento','name'=>'casamento','type'=>'datetime','validation'=>'required','readonly' =>true,'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:255|email','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Idade','name'=>'idade','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tipo','name'=>'TIPO','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'R|Responsável;C|Conjuge;F|Filho / Dependente'];
			$this->form[] = ['label'=>'Cep','name'=>'cep','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Endereço','name'=>'endereco','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			$this->form[] = ['label'=>'Número','name'=>'nroend','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Bairro','name'=>'bairro','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Cidade','name'=>'cidade','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uf','name'=>'uf','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Telefone','name'=>'telefone','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Conjuge','name'=>'conjuge_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'pessoas,nome'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Cpf','name'=>'cpf','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			//$this->form[] = ['label'=>'Nome','name'=>'nome','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			//$this->form[] = ['label'=>'Nome cracha','name'=>'nomecracha','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:255|email|unique:pessoas','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Idade','name'=>'idade','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Tipo','name'=>'TIPO','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'R|Responsável;C|Conjuge;F|Filho / Dependente'];
			//$this->form[] = ['label'=>'Alojamento','name'=>'alojamento','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'CAMPING|Camping;OUTROS|Outros;LAR|Lar;ALOJCOL|Alojamento coletivo'];
			//$this->form[] = ['label'=>'Refeição','name'=>'refeicao','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'QUIOSQUE_COM_CAFE|Quiosque com café;QUIOSQUE_SEM_CAFE|Quiosque sem café'];
			//$this->form[] = ['label'=>'Equipe refeição','name'=>'equipeRefeicao','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'LAR_A|Lar A;LAR_B|Lar B;QUIOSQUE_A|Quiosque A;QUIOSQUE_B|Quiosque B'];
			//$this->form[] = ['label'=>'Presenca confirmada','name'=>'presencaConfirmada','type'=>'radio','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'1|Sim;0|Não'];
			//$this->form[] = ['label'=>'Cep','name'=>'cep','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Endereço','name'=>'endereco','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			//$this->form[] = ['label'=>'Número','name'=>'nroend','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Bairro','name'=>'bairro','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Cidade','name'=>'cidade','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Uf','name'=>'uf','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Telefone','name'=>'telefone','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Conjuge','name'=>'conjuge_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'pessoas,nome'];
			//$this->form[] = ['label'=>'Responsável','name'=>'responsavel_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'pessoas,nome'];
			# OLD END FORM
			

			$columns[] = ['label'=>'Tipo','name'=>'TIPO','type'=>'hidden','value'=>'F'];
			$columns[] = ['label'=>'Nome','name'=>'nome','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
			$columns[] = ['label'=>'Nome cracha','name'=>'nomecracha','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-10'];
			$columns[] = ['label'=>'Idade','name'=>'idade','type'=>'number','validation'=>'integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Filhos / Dependetes','name'=>'filhos','type'=>'child','columns'=>$columns,'table'=>'pessoas','foreign_key'=>'responsavel_id'];

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */

			// $this->sub_module[] = ['label' => 'Dependentes', 
			// 'path' => 'pessoas', 
			// 'foreign_key' => 'responsavel_id', 
			// 'button_color' => 'primary',
			// 'button_icon' => 'fa fa-users',
			// 'parent_columns' => 'nome'];
			
			
			/* 
			| ---------------------------------------------------------------------- 
			| Add More Action Button / Menu
			| ----------------------------------------------------------------------     
			| @label       = Label of action 
			| @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
			| @icon        = Font awesome class icon. e.g : fa fa-bars
			| @color 	   = Default is primary. (primary, warning, succecss, info)     
			| @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
			| 
			*/
			$this->addaction = array();


			/* 
			| ---------------------------------------------------------------------- 
			| Add More Button Selected
			| ----------------------------------------------------------------------     
			| @label       = Label of action 
			| @icon 	   = Icon from fontawesome
			| @name 	   = Name of button 
			| Then about the action, you should code at actionButtonSelected method 
			| 
			*/
			$this->button_selected = array();

					
			/* 
			| ---------------------------------------------------------------------- 
			| Add alert message to this module at overheader
			| ----------------------------------------------------------------------     
			| @message = Text of message 
			| @type    = warning,success,danger,info        
			| 
			*/
			$this->alert        = array();
											
	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
			$this->index_statistic[] = ['label'=>'Total de famílias','count'=>DB::table('pessoas')->where('tipo', 'R')->count(),'icon'=>'fa fa-users','color'=>'green'];
			$this->index_statistic[] = ['label'=>'Total de pessoas','count'=>DB::table('pessoas')->count(),'icon'=>'fa fa-user','color'=>'primary'];

	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    public function getTipo($tipo) {
			if ($tipo == 'R')
				return "Responsável";
			else if ($tipo == "C")
				return "Conjuge";
			else
				return "Filho";
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
			//Your code here
			
			// if (!Request::has('parent_table')){
			// }
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :) 


	}