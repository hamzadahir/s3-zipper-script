'use strict';
(() => {
	let btn_download = $('#download-all-button');
	btn_download.click(()=>{
		let files = [];
		const arrLinks = $('a');
		for(let i=0;i<arrLinks.length;i++) {
			const bucketlink = $(arrLinks[i]).attr('bucketlink');
			if(bucketlink!==undefined) {
				files.push($(arrLinks[i]).attr('bucketlink'));
			}
		}
		//
		if(files.length===0) return;
		//
		btn_download.attr('disabled', "true");
		$.post( "https://ivms-flash.ru/tmp/share/aws-zip.php", { 'files[]': files } )
			.done(function( data ) {
				btn_download.removeAttr("disabled");
				console.log( "Stream link: " + data+"\n" );
				window.open(data);
			});
	})


})();