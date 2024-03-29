<template>
	<div>
		<div class="form-group">
			<label for="event">Nome do evento</label>
			<input type="text" id="event" class="form-control" v-model="pessoa.event" name="event" value="XXIV Congresso de Famílias" />
		</div>
		<div class="form-group">
			<label for="nickname">Apelido</label>
			<input type="text" id="nickname" class="form-control" v-model="pessoa.nickname" name="nickname" value="Matias" />
		</div>
		<div class="form-group">
			<label for="fullname">Nome completo</label>
			<input type="text" id="fullname" class="form-control" v-model="pessoa.fullname" name="fullname" value="Matias Guiomar Henschel" />
		</div>
		<div class="form-group">
			<label for="city">Cidade</label>
			<input type="text" id="city" class="form-control" v-model="pessoa.city" name="city" value="Blumenau - SC" />
		</div>
		<div class="form-group col-6">
			<label for="eatPlace">Refeição - Local</label>
			<select name="eatPlace" class="form-control" v-model="pessoa.eatPlace" id="eatPlace">
				<option value="L">Lar</option>
				<option value="Q">Quiosque</option>
			</select>
		</div>
		<div class="form-group col-6">
			<label for="eatGroup">Refeição - Equipe</label>
			<select name="eatGroup" class="form-control" v-model="pessoa.eatGroup" id="eatGroup">
				<option value="A">A</option>
				<option value="B">B</option>
			</select>
		</div>
		<button class="btn btn-primary" @click="imprimir">Imprimir</button>
	</div>   
</template>

<script>
    import cracha from './cracha';   
    import jsPDF from 'jspdf';
    import swal from 'sweetalert2';

    export default {
        mixins: [cracha],
        data (){
            return{
                pessoa :  {
                    event: 'XXIV Congresso de Famílias',
                    nickname: 'Apelido',
                    fullname: 'Nome completo',
                    city: 'Cidade',
                    eatPlace: 'Q',
                    eatGroup: 'A'
                }
            }
        },
        methods:{
            imprimir: function(){
                var doc = new jsPDF({
                    unit: 'mm',
                    orientation: 'landscape',
                    // https://github.com/parallax/jsPDF/blob/ddbfc0f0250ca908f8061a72fa057116b7613e78/jspdf.js#L791
                    // 72 / 25,4 * 84 = 238
                    // 72 / 25,4 * 50 = 141
                    format: [238, 141]  
                });

                doc.setProperties({
                    title: "Crachas - " + this.pessoa.fullname
                });

                this.generatePdf64(doc, this.pessoa)
                
                var pdf64 = btoa(doc.output());
                var blob = this.b64toBlob(pdf64, 'application/pdf');
                var blobUrl = URL.createObjectURL(blob);

                var w = window.open(blobUrl);

                if (!w){
                    swal('Oops...', 'Para a impressão dos crachas, você deve aceitar abrir popup no browser!', 'warning');
                }else{
                    w.print();
                }                
            }
        }
    }
</script>
