<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Ajax Uploader</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
  </head>
  <body>
      <div class="container">
        <div class="page-header">
          <h1>Simple Ajax Uploader</h1>
          <h3>Basic Example</h3>
        </div>
          <div class="row" style="padding-top:10px;">
            <div class="col-xs-2">
              <button id="uploadBtn" class="btn btn-large btn-primary">Escolha um arquivo</button>
			  <button id="manda">Go</button>
            </div>
            <div class="col-xs-10">
          <div id="progressOuter" class="progress progress-striped active" style="display:none;">
            <div id="progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            </div>
          </div>
            </div>
          </div>
          <div class="row" style="padding-top:10px;">
            <div class="col-xs-10">
              <div id="msgBox">
              </div>
            </div>
          </div>
      </div>

  <script src="../../SimpleAjaxUploader.js"></script>
<script>
function escapeTags( str ) {
  return String( str )
           .replace( /&/g, '&amp;' )
           .replace( /"/g, '&quot;' )
           .replace( /'/g, '&#39;' )
           .replace( /</g, '&lt;' )
           .replace( />/g, '&gt;' );
}

window.onload = function() {

  var btn = document.getElementById('uploadBtn'),
      progressBar = document.getElementById('progressBar'),
      progressOuter = document.getElementById('progressOuter'),
      msgBox = document.getElementById('msgBox');

  var uploader = new ss.SimpleUpload({
        button: btn,
		autoSubmit: false,
        url: 'file_upload.php',
        name: 'uploadfile',
        hoverClass: 'hover',
        focusClass: 'focus',
		maxSize: 2000, //2 MB
        responseType: 'json',
		queue: false,
		onChange: function(){
			uploader.removeCurrent();
		},
        startXHR: function() {
            progressOuter.style.display = 'block'; // make progress bar visible
            this.setProgressBar( progressBar );
        },
		onSizeError: function( filename, fileSize ){
			msgBox.innerHTML = 'O arquivo enviado é muito grande'; // empty the message box
		},
        onSubmit: function() {
            msgBox.innerHTML = ''; // empty the message box
            btn.innerHTML = 'Enviando...'; // change button text to "Uploading..."
          },
        onComplete: function( filename, response ) {
            btn.innerHTML = 'Escolha outro arquivo';
            progressOuter.style.display = 'none'; // hide progress bar when upload is completed

            if ( !response ) {
                msgBox.innerHTML = 'Não foi possível enviar o arquivo';
                return;
            }

            if ( response.success === true ) {
                msgBox.innerHTML = '<strong>' + escapeTags( filename ) + '</strong>' + ' foi enviado.';

            } else {
                if ( response.msg )  {
                    msgBox.innerHTML = escapeTags( response.msg );

                } else {
                    msgBox.innerHTML = 'Ocorreu um erro e não foi possível enviar o arquivo';
                }
            }
          },
        onError: function() {
            progressOuter.style.display = 'none';
            msgBox.innerHTML = 'Não foi possível enviar o arquivo';
          }
	});

	document.getElementById("manda").addEventListener('click', function() { uploader.submit(); }, false);
	
	};
</script>
  </body>
</html>
