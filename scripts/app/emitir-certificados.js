$(document).ready(function(){
	
	if (!app.browserSucks()) {
		$('#selectEventos, #selectAtividades').selectpicker({
			style: 'btn-default btn-large',
			liveSearch: true
		});
		$('#selectAtividades').hide();
	}
	
	$('#selectEventos, #selectAtividades').change(function(){ $(this).selectpicker('setStyle', 'btn-primary btn-large'); });
	
	$('#selectAtividades').change(function(){
		$('#btnContinuar').show();
	});
	
	$('#selectEventos').change(function(){
		$('#loader').removeClass('hidden');
		
		var atividades = $.getJSON(base+'api/palestras?idEvento='+$(this).val());

		atividades.success(function(todasAtividades){
			$('#selectAtividades').empty();
			console.log(todasAtividades.rows);
			$('#selectAtividades').append('<option value="" selected disabled>Atividades</option>');
			$.each( todasAtividades.rows , function(key, value) {
				$('#selectAtividades').append('<option value="'+value.idPalestra+'">'+value.nome+'</option>');				
				if(value.proprioEvento === '1'){
					$('#listaAtividades').hide();	 
					$('#selectAtividades').val(value.idPalestra).show();
					$('#btnContinuar').show();
				 } else {
					$('#listaAtividades').show();
				 }
			});	
			
			$('#selectAtividades').selectpicker('setStyle', 'btn-default btn-large');
			$('#selectAtividades').selectpicker('refresh');
			$('#loader').addClass('hidden');
			  
		});
		
		
	});
	
});