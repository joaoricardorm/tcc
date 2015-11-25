$(document).ready(function(){
	
	if (!app.browserSucks()) {
		$('#selectEventos, #selectAtividades').selectpicker({
			style: 'btn-default btn-large',
			liveSearch: true
		});
		$('#selectAtividades').hide();
	}
	
	$('#btnContinuar').click(function(e){
		if($('#selectAtividades').val() === ''){
			e.preventDefault();
			alert('Escolha uma atividade primeiro');
		}
	});
	
	$('#selectEventos, #selectAtividades').change(function(){ $(this).selectpicker('setStyle', 'btn-primary btn-large'); });
	
	$('#selectAtividades').change(function(){	

		$('#frmAtividade').attr('action',base+'presenca/participantes/'+$('#selectAtividades').val());
	
		$('#btnContinuar').removeClass('hide').addClass('animated fadeInDown').delay(900).queue(function(){ $(this).removeClass('animated'); });
	});
	
	$('#selectEventos').change(function(){
		$('#loader').removeClass('hidden');
		
		var atividades = $.getJSON(base+'api/palestras?idEvento='+$(this).val());

		atividades.success(function(todasAtividades){
			$('#selectAtividades').empty();
			$('#selectAtividades').append('<option value="" selected disabled>Atividades</option>');
			$.each( todasAtividades.rows , function(key, value) {
				$('#selectAtividades').append('<option value="'+value.idPalestra+'">'+value.nome+'</option>');				
				if(value.proprioEvento === '1'){
					$('#listaAtividades').addClass('hide');	 
					$('#selectAtividades').val(value.idPalestra).show();
					
					$('#frmAtividade').attr('action',base+'presenca/participantes/'+$('#selectAtividades').val());
					
					$('#btnContinuar').removeClass('hide').addClass('animated fadeInDown');
				 } else {
					$('#listaAtividades').removeClass('hide').addClass('animated fadeInDown');
				 }
				 
			});	
			
			$('#selectAtividades').selectpicker('setStyle', 'btn-default btn-large');
			$('#selectAtividades').selectpicker('refresh');
			$('#loader').addClass('hidden');
		});		
		
	});
});