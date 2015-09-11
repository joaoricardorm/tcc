<?php
	$this->assign('title','Certificados FAROL | Configuracoes');
	$this->assign('nav','configuracao');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">	
	$LAB
	.script("scripts/jquery-file-upload/js/vendor/jquery.ui.widget.js").wait()
	.script("scripts/jquery-file-upload/js/jquery.iframe-transport.js").wait()
	.script("scripts/jquery-file-upload/js/jquery.fileupload.js").wait()
	.script("scripts/cropit/jquery.cropit.js").wait()
	.script("scripts/jquery.maskedinput-1.1.4.pack.js").wait()
	.script("scripts/app/configuracoes.js").wait(function(){
		$(document).ready(function(){			
			page.init();	
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<link href="scripts/cropit/jquery.cropit.css" type="text/css" rel="stylesheet">
	
<style>
.cropit-image-preview, .zoom {
	width:300px;
	display:block;
	margin:15px auto 0 auto;
	text-align:center;
}
.cropit-image-preview {
	height:250px;
}
</style>

<script type="text/javascript">

</script>

<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="scripts/jquery-file-upload/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="scripts/jquery-file-upload/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads
<script src="scripts/jquery-file-upload/js/jquery.iframe-transport.js"></script>-->
<!-- The basic File Upload plugin
<script src="scripts/jquery-file-upload/js/jquery.fileupload.js"></script>-->
<!-- The File Upload processing plugin -->
<script src="scripts/jquery-file-upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="scripts/jquery-file-upload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload validation plugin -->
<script src="scripts/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
<script>
/*jslint unparam: true, regexp: true */
/*global window, $ */
$(function () {
    'use strict';
	
	//ROOT URL DO PROJETO PARA SALVAR NA PASTA
	var root_url = '<?php $this->eprint(dirname($_SERVER['REQUEST_URI'])); ?>';
	
    // Change this to the location of your server-side upload handler:
    var url = window.location.hostname === 'blueimp.github.io' ?
                '//jquery-file-upload.appspot.com/' : 'scripts/jquery-file-upload/server/php/',
        uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Enviando...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Cancelar')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
    $('#fileupload').fileupload({
        url: url,
		formData: [{ name: 'custom_dir', value: root_url + '/images/uploads/logos/' }],
        dataType: 'json',		
        autoUpload: false,
		limitMultiFileUploads: 1,
		limitConcurrentUploads: 1,
		sequentialUploads: false,
		maxNumberOfFiles: 1,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, //5MB	
		maxNumberOfFiles  : 1,  		
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 250,
        previewMaxHeight: 200,
        previewCrop: true,
		imageMaxWidth: 800, //largura maxima
		imageMaxHeight: 800, //altura maxima
		imageCrop: false // Nao cortar
    }).on('fileuploadadd', function (e, data) {	
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            var node = $('<p/>')
                    .append($('<span/>').text(file.name));
            if (!index) {
                node
                    .append('<br>')
                    .append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Upload')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {
                var link = $('<a>')
                    .attr('target', '_blank')
                    .prop('href', file.url);
                $(data.context.children()[index])
                    .wrap(link);
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>

<?php $this->eprint(dirname($_SERVER['REQUEST_URI'])); ?>

<!-- Generic page styles -->
<link rel="stylesheet" href="scripts/jquery-file-upload/css/style.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="scripts/jquery-file-upload/css/jquery.fileupload.css">
	
	
	

<div class="container">







<!-- The fileinput-button span is used to style the file input field as button -->
<button class="fileinput-button btn btn-primary">
  <i class="icon-picture"></i> Escolher imagem
  <!-- The file input field used as target for the file upload widget -->
  <input id="fileupload" type="file" name="files[]" multiple>
</button>
  
    <!--<span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>-->
    <br>
    <br>
    <!-- The global progress bar -->
    <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <!-- The container for the uploaded files -->
    <div id="files" class="files"></div>








	<h1>
		<i class="icon-cog"></i> Configurações
		<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	</h1>

	<p id="newButtonContainer" class="buttonContainer">
			<button id="newConfiguracaoButton" class="btn btn-primary">Editar configurações</button>
	</p>	

	<!-- underscore template for the collection -->
	<script type="text/template" id="configuracaoCollectionTemplate">
		<table class="collection table table-hover table-striped no-hover">
		<thead>
			<tr>
				<th id="header_NomeInstituicao">Nome da instituição</th>
				<th id="header_ImagemLogo">Imagem de logotipo</th>
				<th id="header_Cnpj">CNPJ</th>
				<th id="header_Telefone">Telefone</th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idConfiguracao')) %>">
				<td><%= _.escape(item.get('nomeInstituicao') || '') %></td>
				<td><img class="img-center" src="images/uploads/logos/<%= _.escape(item.get('imagemLogo') || '') %>" /></td>
				<td><%= _.escape(item.get('cnpj') || '') %></td>
				<td><%= _.escape(item.get('telefone') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="configuracaoModelTemplate">	
		<form class="form-horizontal" enctype="multipart/form-data" onsubmit="return false;">
			<fieldset>
		
		
		
		
		 <div class="image-editor center grey-text text-darken-3">
 
 <!-- The actual file input will be hidden -->
  <!-- And clicking on this button will open up select file dialog -->
  <input type="file" class="cropit-image-input custom" />
  <button class="select-image-btn btn btn-primary">
  <i class="icon-picture"></i> Escolher imagem</button>	 

  <button class="export btn btn-success">
   <i class="icon-ok"></i>
  Salvar</button>  
	  
  <div class="progress hidden">
      <div class="indeterminate"></div>
  </div> 

  <div class="msg-up card-panel blue-grey" style="display:none;">
      <span class="texto white-text"></span>
  </div>
 
 
  <div class="cropit-image-preview"></div>
  
  <div class="zoom">
  <i class="icone-imagem peq hidden-xs"></i>
  
  <p class="range-field">
	<input type="range" class="cropit-image-zoom-input slider left" />
  </p>
  
  <i class="icone-imagem hidden-xs"></i>
  </div>  
 
</div>  
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
				<div id="ImagemInputContainer" class="control-group">
					<label class="control-label" for="imagem">Imagem</label>
					<div class="controls inline-inputs">
						<input type="file" class="input-xlarge" name="imagem" id="imagem" placeholder="Imagem">
						<span class="help-inline"></span>
					</div>
				</div>
				
				
				
				<div id="nomeInstituicaoInputContainer" class="control-group">
					<label class="control-label" for="nomeInstituicao">Nome da instituição</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="nomeInstituicao" placeholder="Nome da instituição" value="<%= _.escape(item.get('nomeInstituicao') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="imagemLogoInputContainer" class="control-group">
					<label class="control-label" for="imagemLogo">Imagem de logotipo</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="imagemLogo" placeholder="Imagem de logotipo" value="<%= _.escape(item.get('imagemLogo') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="cnpjInputContainer" class="control-group">
					<label class="control-label" for="cnpj">CNPJ</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="cnpj" placeholder="CNPJ" value="<%= _.escape(item.get('cnpj') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="telefoneInputContainer" class="control-group">
					<label class="control-label" for="telefone">Telefone</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="telefone" placeholder="Telefone" value="<%= _.escape(item.get('telefone') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>
	

	
	
	
	
	
	
	
	

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="configuracaoDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Editar Configuracão
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="configuracaoModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveConfiguracaoButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="configuracaoCollectionContainer" class="collectionContainer">
	</div>
</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
			
