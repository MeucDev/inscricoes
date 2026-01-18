<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\LinkInscricao;
	use Carbon\Carbon;

	class AdminLinksInscricaoController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "token";
			$this->limit = "20";
			$this->orderby = "data_geracao,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "link_inscricoes";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			$this->evento = (int)request()->get('parent_id');
			if ($this->evento == 0)
				$this->evento = (int)request()->get('evento_id');

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Tipo de Inscrição","name"=>"tipo_inscricao","callback"=>function($row) {
				$tipos = [
					'NORMAL' => '<span class="label label-primary">NORMAL</span>',
					'BANDA' => '<span class="label label-info">BANDA</span>',
					'COMITE' => '<span class="label label-warning">COMITE</span>',
					'STAFF' => '<span class="label label-success">STAFF</span>'
				];
				return isset($tipos[$row->tipo_inscricao]) ? $tipos[$row->tipo_inscricao] : $row->tipo_inscricao;
			}];
			$this->col[] = ["label"=>"Limite de Uso","name"=>"limite_uso"];
			$this->col[] = ["label"=>"Uso Atual","name"=>"uso_atual"];
			$this->col[] = ["label"=>"Data de Expiração","name"=>"data_expiracao","callback"=>function($row) {
				if (!$row->data_expiracao) return '-';
				$dataExpiracao = Carbon::parse($row->data_expiracao);
				$agora = Carbon::now();
				$formatoData = $dataExpiracao->format('d/m/Y H:i');
				
				// Adicionar indicador visual na data baseado no status
				if ($dataExpiracao->lessThan($agora)) {
					return '<span class="text-danger">' . $formatoData . ' <span class="label label-danger">Expirado</span></span>';
				} elseif ($row->uso_atual >= $row->limite_uso) {
					return '<span class="text-warning">' . $formatoData . ' <span class="label label-warning">Limite Atingido</span></span>';
				} else {
					return '<span class="text-success">' . $formatoData . ' <span class="label label-success">Ativo</span></span>';
				}
			}];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Evento','name'=>'evento_id','type'=>'hidden', 'value'=>$this->evento];
			$this->form[] = ['label'=>'Token','name'=>'token','type'=>'text','validation'=>'required','readonly'=>true,'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tipo de Inscrição','name'=>'tipo_inscricao','type'=>'select','validation'=>'required','width'=>'col-sm-10','dataenum'=>'NORMAL|NORMAL;BANDA|BANDA;COMITE|COMITE;STAFF|STAFF'];
			$this->form[] = ['label'=>'Limite de Uso','name'=>'limite_uso','type'=>'number','validation'=>'required|integer|min:1','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uso Atual','name'=>'uso_atual','type'=>'number','validation'=>'required|integer|min:0','readonly'=>true,'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Data de Geração','name'=>'data_geracao','type'=>'datetime','validation'=>'required','readonly'=>true,'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Data de Expiração','name'=>'data_expiracao','type'=>'datetime','validation'=>'required','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

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
			// Não incluir parent_id na URL quando filtrando por link_inscricao_id para evitar conflitos
			$urlInscricoes = CRUDBooster::adminPath('inscricoes?link_inscricao_id=[id]');
			
			$this->addaction[] = ['label'=>'Inscrições','url'=>$urlInscricoes,'icon'=>'fa fa-list','color'=>'info'];

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
	        $this->alert = array();
	                

	        
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
	        $this->index_statistic = array();



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
			if ($this->evento > 0) {
				$query->where('evento_id', $this->evento);
			}
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