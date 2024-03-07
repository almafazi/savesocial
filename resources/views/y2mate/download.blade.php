
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Download MP3 - YTJar.info</title>
<style>html,body{height:100%;padding:0;margin:0;overflow:hidden}body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";color:#5e5b64;font-size:24px}@media(max-height:150px) and (max-width:400px){body{font-size:18px}}@media(max-width:200px){body{font-size:14px}}/*button{font-size:1em;border-radius:2px;border:0;height:100%;width:100%;padding:0;margin:0;cursor:pointer}*/.progress-button{display:inline-block;font-size:18px;color:#fff!important;text-decoration:none!important;line-height:1;overflow:hidden;position:relative;text-align:center;width:100%;height:100%;box-shadow:0 1px 1px #ccc;border-radius:2px;cursor:pointer;background-color:#64c896}.buttonTitle{font-size:9px;margin-top:4px}#container{height:100%;text-align:center}#container:before{content:'';display:inline-block;vertical-align:middle;height:100%}#percentageText{width:95%;display:inline-block;position:relative;vertical-align:middle;z-index:3}</style>

<script src="https://mp3api.ytjar.info/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.3.1/iframeResizer.contentWindow.js"></script>
<style>
.progress-button{
	background-color: #EEEEEE!important;
	background-image: none;		
	color: #FF0000!important;
}
.filesize {
    display: block;
    margin-top: 5px;
    color: brown;
}
</style>
</head>
<body>
<a id="downloadButton" class="dmtrigger" href="javascript:;" data-ok="1">
<div id="container" class="progress-button">
<div id="percentageText">
<span id="dt">{{ 'Download MP3' }}</span><div class="buttonTitle">
    {{-- <span>{{ $video->title ?? $video->getTitle() }}</span> --}}
    {{-- <span class="filesize">{{  humanFilesize($video->filesize ?? $video->getFilesize()) }}</span> --}}
</div>
</div>
</div>
</a>
<div id="cfToken"></div>
<script>
var cfToken = null;
var turnstileWID = null;
var expCookie = 'm3aytj_dlexp';
@if(!$notfound)
$(document).on('click', 'a.dmtrigger', function(e) {
	e.preventDefault();
	$('#dt').text('Generating Links...');
	var ok = $(this).attr("data-ok");
	if(ok == '1'){
		$('#downloadButton').attr("data-ok", "0");
		mp3Conversion('{{ $id }}');	
		$('#downloadButton').attr("data-ok", "1");		
	}
});
@endif
function mp3Conversion(id, cfToken = null){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
	$.ajax({
		type: 'POST',
		url: '{{ route("index.y2mate.convert") }}',
		data: {
            'id': '{{ $id }}',
            's': window.tS,
            'h': window.tH
        },
		success: function(data, textStatus, request){				
			        $("#downloadButton").removeClass("dmtrigger");
			        if(data.status == "ok") {
				        if(typeof turnstile !== "undefined"){
                            turnstile.remove(window.turnstileWID);
                        }
				$('#dt').text('Download MP3');
				var dlink = data.link + '&dom=Iframe';
				$("#downloadButton").attr("href",dlink);				
				$("body").append('<iframe src="' + dlink + '" style="display: none;" ></iframe>');
	
			} else if (data.status == "processing"){
				if(data.progress){
					if(parseInt(data.progress) < 10){
						$("#dt").text('Converting ' + '10%'); 
					}else{
						$("#dt").text('Converting ' + data.progress+'%');
					}
				}
				setTimeout(function(){mp3Conversion(id, cfToken + ".PROGRESS")}, 2000);
						} else {
				$('#dt').text('Download Error !');
				$('.buttonTitle').text(data.msg);
				if(typeof gtag !== "undefined"){
					gtag('event', 'MP3download', {
					  'event_category': 'Error',
					  'event_label': id + ' ' + data.msg
					});
				}
			}			
		},
		error : function(xhr, status, ex) {
            console.log(xhr);
			// var err = JSON.parse(xhr.responseText);				
			//ga('send', 'event', 'Download_MP3', 'failed2', id + ' ' + xhr.responseText + ' ' + ex + err.Message);
			// if(typeof gtag !== "undefined"){
			// 	gtag('event', 'MP3download', {
			// 	  'event_category': 'Failed',
			// 	  'event_label': id + ' ' + xhr.responseText + ' ' + ex + err.Message
			// 	});
			// }
			// setTimeout(function(){mp3Conversion(id, window.cfToken)}, 1);
		}
	});
}
function ping(u){
	$.ajax({
        url: u,
        success: function(result){
			console.log('pinged');
        },     
        error: function(result){
			console.log('pinge error');
		}
	});
}
</script>

<div style="display:none">103.165.157.34 - Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36</div></body>
</html>
