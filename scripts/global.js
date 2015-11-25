//Habilita o onpopstate para botoes voltar e avancar funcionarem
window.onpopstate = function(e) {
	if (e.state) {
		window.location.reload();
	}
};

//DIMINUI O TOPO AO ROLAR
$(window).scroll(function() {
	if($(window).width() > 767){
		 var a=$(window).scrollTop();
		 if(a > 100){
			$('.navbar').addClass('scroll');
		 } else {
			$('.navbar').removeClass('scroll');
		 }
	}
});	

isMobile = false;
if(navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry|IEMobile)/)) {
 isMobile = true;
}



//DOM ELEMENT EM FULLSCREEN

document.cancelFullScreen = document.webkitExitFullscreen || document.mozCancelFullScreen || document.exitFullscreen;

var elem = document.querySelector(document.webkitExitFullscreen ? ".fs" : "#fs-container");
var zoomInicial = $('.containerA4preview').css('zoom');

function toggleFS(el) {
  if (el.webkitEnterFullScreen) {
    el.webkitEnterFullScreen();
  } else {
    if (el.mozRequestFullScreen) {
      el.mozRequestFullScreen();
    } else {
      el.requestFullscreen();
    }
  }

  el.ondblclick = exitFullscreen;
}

jQuery.fn.fitToParent = function(pai){
	var that = this;


	   that.each(function()
	   {
		 var a = $(this).width();
		 var b = $(this).height();
		 var c = pai.width();
		 var d = pai.height();

		 var ab = a/b;
		 var cd = c/b;

		 var e, f = 0; // e - newWidth, f - newHeight

		 if(ab > cd) {
			e = c;
			f = c*b/a;
		 } else {
			e = a*d/b;
			f = d;
		 }

		 $(this).width(e);
		 $(this).height(f);
	   });

 };
	 

function onFullScreenEnter() {
  console.log("Entered fullscreen!");
  elem.onwebkitfullscreenchange = onFullScreenExit;
  elem.onmozfullscreenchange = onFullScreenExit;
};

// Called whenever the browser exits fullscreen.
function onFullScreenExit() {
  console.log("Exited fullscreen!");
};

// Note: FF nightly needs about:config full-screen-api.enabled set to true.
function enterFullscreen() {
  console.log("enterFullscreen()");
  elem.onwebkitfullscreenchange = onFullScreenEnter;
  elem.onmozfullscreenchange = onFullScreenEnter;
  elem.onfullscreenchange = onFullScreenEnter;
  if (elem.webkitRequestFullscreen) {
    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
  } else {
    if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else {
      elem.requestFullscreen();
    }
  }
  document.getElementById('enter-exit-fs').onclick = exitFullscreen;
}

function exitFullscreen() {
  console.log("exitFullscreen()");
  document.cancelFullScreen();
  document.getElementById('enter-exit-fs').onclick = enterFullscreen;  
  $('#enter-exit-fs').blur();
}


if (document.addEventListener)
{
    document.addEventListener('webkitfullscreenchange', exitHandler, false);
    document.addEventListener('mozfullscreenchange', exitHandler, false);
    document.addEventListener('fullscreenchange', exitHandler, false);
    document.addEventListener('MSFullscreenChange', exitHandler, false);
}

telaCheia = false;
//REDIMENSIONA FOLHA NA TELA CHEIA
function exitHandler()
{

		if(telaCheia === false)
			telaCheia = true;
		else
			telaCheia = false;
		 
		if (telaCheia === true) {
			//not full screen
			if(!isMobile){			   
			  zoom = 1; 
			  do { 
				$('.containerA4preview').css('zoom',zoom);
				console.log('div', $('.containerA4preview').height()*zoom, 'window', window.screen.height);
				zoom = zoom+0.1; 
			  } while($('.containerA4preview').height()*zoom < window.screen.height);

			}
		} else {
			if(!isMobile){
				$('.containerA4preview').attr('style','');
			}
		}
    
}

//CONVERSOR RGB TO HEX
var hexDigits = new Array
	("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

//Function to convert hex format to a rgb color
function rgb2hex(rgb) {
rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}





	