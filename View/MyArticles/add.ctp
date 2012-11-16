<style type="text/css">
.mceEditor * {
    padding: 0;
}
</style>

<div class="articles form">
<?php echo $this->Form->create('Article');?>
	<fieldset>
		<legend><?php echo __('Add Article'); ?></legend>
	<?php
		echo $this->Form->input('subject');
		echo $this->Form->input('text', array('rows' => 30));

        $is_html = 1;
        if ($use_texteditor) {
            $is_html = 0;
        }
        echo $this->Form->input('is_html', array('type' => 'hidden', 'value' => $is_html));
        echo $this->Form->input('text', array('type' => 'text', 'rows' => 30,
                    'value' => '', 'id' => 'wysiwyg-editor', 'class' => 'tinymce')); ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Articles'), array('action' => 'index'));?></li>
	</ul>
</div>


<?php ob_start(); ?>
$(function() {
        $('textarea.tinymce').tinymce({
                // Location of TinyMCE script
                script_url : '<?= Router::url('/js/tiny_mce/tiny_mce.js') ?>',

                // General options
                theme : "simple",
                plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

                // Theme options
                theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,

                // Example content CSS (should be your site CSS)
                content_css : "<?= Router::url('/css/tiny_mce/content.css') ?>",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url : "lists/template_list.js",
                external_link_list_url : "lists/link_list.js",
                external_image_list_url : "lists/image_list.js",
                media_external_list_url : "lists/media_list.js",

                // Replace values for the template plugin
                template_replace_values : {
                        username : "Some User",
                        staffid : "991234"
                }
        });
});
<?php 
$init_tinymce = ob_get_clean();

$this->Html->script('tiny_mce/jquery.tinymce.js', false);
echo $this->Html->scriptBlock($init_tinymce);
?>
