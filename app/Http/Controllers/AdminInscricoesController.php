<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Valor;
	use App\Variacao;
	use App\Inscricao;
	use App\Pessoa;
	use App\Evento; 
	
	class AdminInscricoesController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->primary_key = "numero";
			$this->title_field = "numero";
			$this->limit = "20";
			$this->orderby = "numero,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "inscricoes";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			$this->evento = (int)Request::get('parent_id');
			if ($this->evento == 0)
				$this->evento = (int)Request::get('evento_id');

			$this->filter_column = [];
			$this->filter_column[] = [
				'cancelada'=>[
					'label'=>'Cancelada',
					'type'=>'int',
					'default_value'=>0
				]
			];

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Número","name"=>"numero"];
			$this->col[] = ["label"=>"Resp.","name"=>"pessoa_id","join"=>"pessoas,nome"];
			$this->col[] = ["label"=>"Cidade","name"=>"pessoa_id","join"=>"pessoas,cidade"];
			$this->col[] = ["label"=>"Total","name"=>"valorTotal"];
			$this->col[] = ["label"=>"Pago","name"=>"valorTotalPago"];
			$this->col[] = ["label"=>"Canc.?","name"=>"cancelada","callback"=>function($row) {
				return ($row->cancelada == 1) ? '<span class="label label-danger">sim</span>' : '<span class="label label-success">não</span>';
			}];
			$this->col[] = ["label"=>"Pagou?","name"=>"inscricaoPaga","callback"=>function($row) {
				return ($row->inscricaoPaga == 1) ? '<span class="label label-success">sim</span>' : '<span class="label label-danger">não</span>';
			}];
			$this->col[] = ["label"=>"Presente?","name"=>"presencaConfirmada","callback"=>function($row) {
				return ($row->presencaConfirmada == 1) ? '<span class="label label-success">sim</span>' : '<span class="label label-danger">não</span>';
			}];
			$this->col[] = ["label"=>"Inscritos","name"=>"(select count(*) from inscricoes ins where ins.numero_inscricao_responsavel = inscricoes.numero) + 1 as total_inscritos","callback"=>function($row) {
				return '<span class="badge bg-yellow">'. $row->total_inscritos .'</span>';
			}];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Evento','name'=>'evento_id','type'=>'hidden', 'value'=>$this->evento];
			$this->form[] = ['label'=>'Número','name'=>'numero','type'=>'number','validation'=>'required|integer|min:0','readonly' =>true,'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Data','name'=>'dataInscricao','type'=>'datetime','validation'=>'required','readonly' =>true,'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Pessoa','name'=>'pessoa_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'pessoas,nome', 'disabled' => true];
			$this->form[] = ['label'=>'Alojamento','name'=>'alojamento','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'CAMPING|Camping;OUTROS|Outros;LAR|Lar Filadélfia','readonly' =>true];
			$this->form[] = ['label'=>'Refeição','name'=>'refeicao','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'QUIOSQUE_COM_CAFE|Quiosque com café;QUIOSQUE_SEM_CAFE|Quiosque sem café;LAR_COM_CAFE|Lar com café;LAR_SEM_CAFE|Lar sem café;LAR|Lar Filadélfia (tratar direto);NENHUMA|Nenhuma','readonly' =>true];
			$this->form[] = ['label'=>'Equipe refeição','name'=>'equipeRefeicao','type'=>'select','width'=>'col-sm-10','dataenum'=>'LAR_A|Lar A;LAR_B|Lar B;QUIOSQUE_A|Quiosque A;QUIOSQUE_B|Quiosque B','readonly' =>true];
			$this->form[] = ['label'=>'Cancelada?','name'=>'cancelada','type'=>'radio','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'1|Sim;0|Não'];
			$this->form[] = ['label'=>'Pagou?','name'=>'inscricaoPaga','type'=>'radio','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'1|Sim;0|Não'];
			$this->form[] = ['label'=>'Presença confirmada','name'=>'presencaConfirmada','type'=>'radio','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'1|Sim;0|Não'];
			$this->form[] = ['label'=>'Valor inscricão','name'=>'valorInscricao','type'=>'number','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Valor inscricão pago','name'=>'valorInscricaoPago','type'=>'number','width'=>'col-sm-10', 'readonly' =>true];
			$this->form[] = ['label'=>'Valor alojamento','name'=>'valorAlojamento','type'=>'number','width'=>'col-sm-10', 'readonly' =>true];
			$this->form[] = ['label'=>'Valor refeição','name'=>'valorRefeicao','type'=>'number','width'=>'col-sm-10', 'readonly' =>true];
			$this->form[] = ['label'=>'Valor total','name'=>'valorTotal','type'=>'number','width'=>'col-sm-10', 'readonly' =>true];
			$this->form[] = ['label'=>'Valor total pago','name'=>'valorTotalPago','type'=>'number','width'=>'col-sm-10', 'readonly' =>true];
			$this->form[] = ['label'=>'PagSeguro code','name'=>'pagseguroCode','type'=>'text','validation'=>'','readonly' => true, 'width'=>'col-sm-10'];
			$this->form[] = ['label'=>'PagSeguro Link','name'=>'pagseguroLink','type'=>'text','validation'=>'','readonly' => true, 'width'=>'col-sm-10'];

			//$this->form[] = ['label'=>'Observação','name'=>'observacao','type'=>'wysiwyg','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Numero','name'=>'numero','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'DataInscricao','name'=>'dataInscricao','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'InscricaoPaga','name'=>'inscricaoPaga','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Observacao','name'=>'observacao','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'TipoInscricao','name'=>'tipoInscricao','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'ValorInscricao','name'=>'valorInscricao','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'ValorInscricaoPago','name'=>'valorInscricaoPago','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'ValorTotal','name'=>'valorTotal','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'ValorTotalPago','name'=>'valorTotalPago','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Responsavel Id','name'=>'responsavel_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'responsavel,id'];
			//$this->form[] = ['label'=>'Ano','name'=>'ano','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			# OLD END FORM

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
			$this->sub_module[] = ['label'=>'Hist. pagamentos','path'=>'historico_pagamentos','custom_parent_id'=>'numero','parent_columns'=>'numero','foreign_key'=>'inscricao_numero','button_color'=>'info','button_icon'=>'fa fa-dollar'];
			
			$this->sub_module[] = ['label'=>'Dep.','path'=>'inscricoes','custom_parent_id'=>'numero','parent_columns'=>'numero','foreign_key'=>'numero_inscricao_responsavel','button_color'=>'primary','button_icon'=>'fa fa-folder'];
			

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

			$presenca = 'javascript:modalApp.show("Confirmar presença", "presenca", {id: [numero]})';
			$eventoObj = $this->getEventoObject();
			$editar = 'javascript:modalApp.show("Edição", "inscricao", {interno: true, inscricao: [numero], evento:'. $eventoObj .'})';

			$this->addaction[] = ['label'=>'Confirmar','url'=>$presenca,'icon'=>'fa fa-check','color'=>'success'];			
			$this->addaction[] = ['label'=>'Editar','url'=>$editar,'icon'=>'fa fa-pencil','color'=>'primary'];			


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
			
			$eventoObj = $this->getEventoObject();
			$gerarLink = 'javascript:gerarLinkInscricao('. $this->evento .', '. $eventoObj .')';
			$cracha = 'javascript:modalApp.show("Cracha", "cracha", {})';

			$this->index_button[] = ["label"=>"Gerar link inscrição","icon"=>"fa fa-link","url"=>$gerarLink];
			$this->index_button[] = ["label"=>"Imprimir cracha customizado","icon"=>"fa fa-print","url"=>$cracha];			

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
			
			$this->index_statistic[] = ['label'=>'Total de famílias / pessoas',
				'count'=>
					DB::table('inscricoes')
					->select(
						DB::raw("CONCAT(
							SUM(case when numero_inscricao_responsavel is null then 1 else 0 end),
							' / ', COUNT(*)
						) as resultado"))
					->where('evento_id', $this->evento)
					->where('cancelada', 0)
					->where(function($query) {
						$query->where("tipoInscricao", "NORMAL")
							  ->orWhereNull("tipoInscricao");
					})
					->first()->resultado,
				'icon'=>'ion ion-person-stalker','color'=>'aqua'];

			$this->index_statistic[] = ['label'=>'Famílias banda / pessoas',
				'count'=>
					DB::table('inscricoes')
					->select(
						DB::raw("CONCAT(
							SUM(case when numero_inscricao_responsavel is null then 1 else 0 end),
							' / ', COUNT(*)
						) as resultado"))
					->where('evento_id', $this->evento)
					->where('cancelada', 0)
					->where('tipoInscricao', 'BANDA')
					->first()->resultado,
				'icon'=>'fa fa-music','color'=>'purple'];

			$this->index_statistic[] = ['label'=>'Famílias comitê / pessoas',
				'count'=>
					DB::table('inscricoes')
					->select(
						DB::raw("CONCAT(
							SUM(case when numero_inscricao_responsavel is null then 1 else 0 end),
							' / ', COUNT(*)
						) as resultado"))
					->where('evento_id', $this->evento)
					->where('cancelada', 0)
					->where('tipoInscricao', 'COMITE')
					->first()->resultado,
				'icon'=>'fa fa-users','color'=>'blue'];

			$this->index_statistic[] = ['label'=>'Famílias staff / pessoas',
				'count'=>
					DB::table('inscricoes')
					->select(
						DB::raw("CONCAT(
							SUM(case when numero_inscricao_responsavel is null then 1 else 0 end),
							' / ', COUNT(*)
						) as resultado"))
					->where('evento_id', $this->evento)
					->where('cancelada', 0)
					->where('tipoInscricao', 'STAFF')
					->first()->resultado,
				'icon'=>'fa fa-star','color'=>'orange'];

			$this->index_statistic[] = ['label'=>'Presentes: total de famílias / pessoas ',
				'count'=>DB::table('inscricoes')
				->select(
					DB::raw("CONCAT(
						SUM(case when numero_inscricao_responsavel is null then 1 else 0 end),
						' / ', COUNT(*)
					) as resultado"))
				->where([['presencaConfirmada', '1'] , ['evento_id', $this->evento]])
				->first()->resultado,
				'icon'=>'fa fa-check','color'=>'green'];

			$this->index_statistic[] = ['label'=> 'Equipes quiosque'
				,'count'=> 
				DB::table('inscricoes')
					->select(
						DB::raw("CONCAT(
							'A: ', SUM(case when equipeRefeicao = 'QUIOSQUE_A' then 1 else 0 end),
							' B: ', SUM(case when equipeRefeicao = 'QUIOSQUE_B' then 1 else 0 end)
						) as equipes"))
					->where('presencaConfirmada', '1')
					->where('evento_id', $this->evento)
					->first()->equipes
				,'icon'=>'fa fa-cutlery','color'=>'red'];	
			$this->index_statistic[] = ['label'=> 'Equipes lar'
				,'count'=> 
				DB::table('inscricoes')
					->select(
						DB::raw("CONCAT(
							'A: ', SUM(case when equipeRefeicao = 'LAR_A' then 1 else 0 end),
							' B: ', SUM(case when equipeRefeicao = 'LAR_B' then 1 else 0 end)
						) as equipes"))
					->where('presencaConfirmada', '1')
					->where('evento_id', $this->evento)
					->first()->equipes
				,'icon'=>'fa fa-cutlery','color'=>'yellow'];							

			/*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
			*/
			
			$eventoObj = $this->getEventoObject();
			$novo = 'javascript:modalApp.show("Nova inscrição", "inscricao", {interno: true, evento:'. $eventoObj .'})';
			
			$this->script_js = "
				$(function() {
					$('#btn_add_new_data').attr('href', '". $novo ."');
				});
				
				function copyToClipboard(text, inputId) {
					var input = document.getElementById(inputId);
					if (input) {
						input.select();
						input.setSelectionRange(0, 99999);
					}
					var textarea = document.createElement('textarea');
					textarea.value = text;
					textarea.style.position = 'fixed';
					textarea.style.opacity = '0';
					document.body.appendChild(textarea);
					textarea.select();
					try {
						document.execCommand('copy');
						alert('Link copiado para a área de transferência!');
					} catch (err) {
						alert('Não foi possível copiar o link');
					}
					document.body.removeChild(textarea);
				}
				
				function atualizarLinkInscricao(eventoId) {
					var tipo = document.getElementById('modal-link-tipo').value;
					var dataAtual = new Date().toISOString().split('T')[0];
					var bypassBase64 = btoa(dataAtual);
					var typeBase64 = btoa(tipo);
					var urlBase = window.location.origin;
					var linkGerado = urlBase + '/eventos/' + eventoId + '?bypass=' + bypassBase64 + '&type=' + typeBase64;
					document.getElementById('modal-link-input').value = linkGerado;
				}
				
				function gerarLinkInscricao(eventoId, eventoObj) {
					var dataAtual = new Date().toISOString().split('T')[0];
					var bypassBase64 = btoa(dataAtual);
					var typeBase64 = btoa('NORMAL');
					var urlBase = window.location.origin;
					var linkInicial = urlBase + '/eventos/' + eventoId + '?bypass=' + bypassBase64 + '&type=' + typeBase64;
					
					var modalHtml = '<div class=\"modal fade\" id=\"modalGerarLink\" tabindex=\"-1\" role=\"dialog\">' +
						'<div class=\"modal-dialog\" role=\"document\" style=\"max-width: 600px;\">' +
						'<div class=\"modal-content\">' +
						'<div class=\"modal-header\">' +
						'<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">' +
						'<span aria-hidden=\"true\">&times;</span>' +
						'</button>' +
						'<h4 class=\"modal-title\">Gerar Link de Inscrição</h4>' +
						'</div>' +
						'<div class=\"modal-body\">' +
						'<div class=\"form-group\">' +
						'<label for=\"modal-link-tipo\" style=\"font-weight: bold;\">Selecione o tipo de inscrição:</label>' +
						'<select id=\"modal-link-tipo\" class=\"form-control\" onchange=\"atualizarLinkInscricao(' + eventoId + ')\">' +
						'<option value=\"NORMAL\">NORMAL</option>' +
						'<option value=\"BANDA\">BANDA</option>' +
						'<option value=\"COMITE\">COMITE</option>' +
						'<option value=\"STAFF\">STAFF</option>' +
						'</select>' +
						'</div>' +
						'<div class=\"form-group\">' +
						'<label for=\"modal-link-input\" style=\"font-weight: bold;\">Link gerado:</label>' +
						'<div class=\"input-group\">' +
						'<input type=\"text\" id=\"modal-link-input\" class=\"form-control\" value=\"' + linkInicial + '\" readonly style=\"font-size: 12px;\">' +
						'<span class=\"input-group-btn\">' +
						'<button class=\"btn btn-primary\" type=\"button\" onclick=\"copyToClipboard(document.getElementById(\\'modal-link-input\\').value, \\'modal-link-input\\')\">' +
						'<i class=\"fa fa-copy\"></i> Copiar' +
						'</button>' +
						'</span>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'<div class=\"modal-footer\">' +
						'<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fechar</button>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'</div>';
					
					$('#modalGerarLink').remove();
					$('body').append(modalHtml);
					$('#modalGerarLink').modal('show');
				}
			";

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
			$parent_id = g('parent_id');
    		$parent_table = g('parent_table');

			if($parent_id && $parent_table) {
				if($parent_table === 'eventos') {
					$query->whereNull('numero_inscricao_responsavel');
				}
				else if ($parent_table === 'inscricoes') {
					$query->where('numero_inscricao_responsavel', $parent_id);
				}
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

		}

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
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
			$inscricao = Inscricao::find($id);

			$inscricao->calcularTotais();
			$inscricao->save();
		}
		
	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
			Inscricao::where("numero_inscricao_responsavel", $id)->delete();
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

		private function getEventoObject() {
			if ($this->evento > 0) {
				$evento = Evento::find($this->evento);
				if ($evento) {
					$eventoData = [
						'id' => $evento->id,
						'nome' => $evento->nome,
						'registrar_data_casamento' => isset($evento->registrar_data_casamento) ? $evento->registrar_data_casamento : 1
					];
					return json_encode($eventoData);
				}
			}
			// Fallback se não encontrar o evento
			return json_encode(['id' => $this->evento, 'nome' => '', 'registrar_data_casamento' => 1]);
		}


	}