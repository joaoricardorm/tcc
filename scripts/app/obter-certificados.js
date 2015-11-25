$(document).ready(function(){
	
app.alertaAnimado('#btnObterAta', 'click', '#alertaDownloadAta',4000);

function toggleBtnObterCertificados(){
	if($("input:checkbox:checked").length > 0) {
        $('#btnObterCertificados').removeClass('hide').addClass('animated fadeInDown').delay(900).queue(function(){ $(this).removeClass('animated'); });
    } else {
		$('#btnObterCertificados').addClass('hide');
	}
}

toggleBtnObterCertificados();
$("input[type=checkbox]").change(function() {
    toggleBtnObterCertificados();
});

app.hideProgress('loader');
	
});