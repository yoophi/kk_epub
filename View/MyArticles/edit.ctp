<?
echo $this->Html->script('require-jquery.js', array(
    'data-main' => $this->Html->url('/js/main-app-photo'),
    'inline' => false
    ));
?>
<div class="articles form">
<?php echo $this->Form->create('Article');?>
	<fieldset>
		<legend><?php echo __('Edit Article'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('subject');

        $is_html = 1;
        if ($use_texteditor) {
            $is_html = 0;
        }
        echo $this->Form->input('is_html', array('type' => 'hidden', 'value' => $is_html));
        echo $this->Form->input('category_id', array('type' => 'select', 'value' => $this->request->data['Article']['category_id']));
    ?>
        <div id="wysiwyg-editor_container">
    <?php
		echo $this->Form->input('text', array('type' => 'text', 'rows' => 30, 'value' => $content, 'class' => 'tinymce'));
	?>
        </div>
	</fieldset>

	<div id="photo-app">
		<input id="fileupload" type="file" name="files[]" multiple>
		<div id="photo-list"></div>
	</div>

<?php echo $this->Form->end(__('Submit'));?>

</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Article.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Article.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Articles'), array('action' => 'index'));?></li>
	</ul>
</div>


<script type="text/template" id="photo-template">
<div><img src="${thumb_url}" data-url="${url}" class="photo-item" /> ${filename} <a class="photo-destroy">destroy</a></div>
</script>
