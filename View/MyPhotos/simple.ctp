<input id="fileupload" type="file" name="files[]" multiple>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?= $this->Html->url('/js/jquery.ui.widget.js'); ?>"></script>
<script src="<?= $this->Html->url('/js/jquery.iframe-transport.js'); ?>"></script>
<script src="<?= $this->Html->url('/js/jquery.fileupload.js'); ?>"></script>

<script>
$(function() {
    $('#fileupload').fileupload({
		url: '<?= Configure::read("Site.MU_URL") ?>'
		, forceIframeTransport: true
	});

    $('#fileupload').fileupload(
        'option',
        'redirect',
        '<?= Router::url("/result.html", true) ?>?%s'
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
