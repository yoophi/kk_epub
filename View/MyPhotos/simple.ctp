<!--
<input id="fileupload" type="file" name="files[]" data-url="/jQuery-File-Upload/server/php/" multiple>
<input id="fileupload" type="file" name="files[]" data-url="http://172.16.97.138/blueimp_file_handler/attachments" multiple>
-->
<input id="fileupload" type="file" name="files[]" multiple>


<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?= $this->Html->url('/js/jquery.ui.widget.js'); ?>"></script>
<script src="<?= $this->Html->url('/js/jquery.iframe-transport.js'); ?>"></script>
<script src="<?= $this->Html->url('/js/jquery.fileupload.js'); ?>"></script>

<script>
$(function() {
    $('#fileupload').fileupload({
		url: 'http://172.16.97.138/blueimp_file_handler/attachments'
		, forceIframeTransport: true
	});

    $('#fileupload').fileupload(
        'option',
        'redirect',
        'http://npub2.vm/kk_epub2/result.html?%s'
    );

	$('#fileupload').fileupload({
		dataType: 'json',
		add: function (e, data) {
			console.log('- add -');
			data.context = $('<p />').text('uploading...').appendTo(document.body);
			data.formData = { 'hello': 'Hello, world!' };
			console.log(data);
			data.submit();
		},
		done: function (e, data) {
			console.log('- done -');
			data.context.text('Upload finished.');
			$.each(data.result, function(index, file) {
				$('<p/>').text(file.name).appendTo(document.body);
				console.log(file);
			});
		}
	});
});
</script>
