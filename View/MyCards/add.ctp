<style type="text/css">
    .mceEditor * {
        padding: 0;
    }

    div.submit {
        margin-top: 1em;
    }
</style>

<div class="articles form">
    <div class="page-header">
        <h1><?php echo __('My Card');?></h1>
    </div>

    <?php echo $this->Form->create('Card');?>
    <fieldset>
        <legend>새로운 카드 추가</legend>
        <?php
        echo $this->Form->input('subject');
        echo $this->Form->input('category_id', array('type' => 'select'));

        $is_html = 1;
        if ($use_texteditor) {
            $is_html = 0;
        }
        echo $this->Form->input('is_html', array('type' => 'hidden', 'value' => $is_html));
        echo $this->Form->input('content_html', array(
            'type' => 'textarea',
            'id' => 'wysiwyg-editor',
            'class' => 'tinymce',
            'style' => 'width: 320px; height: 480px',
        ));
        echo $this->Form->submit("카드 생성", array('class' => 'btn'));
        ?>
    </fieldset>
    <?php echo $this->Form->end();?>
</div>

<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('List Cards'), array('action' => 'index'));?></li>
    </ul>
</div>

<?php ob_start(); ?>
$(function() {
    $('textarea.tinymce').tinymce({
        // Location of TinyMCE script
        script_url : '<?= Router::url('/js/tiny_mce/tiny_mce.js') ?>',

        // General options
        // theme : "simple",
        theme : "advanced",
        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,fullscreen",
        theme_advanced_buttons4 : "visualchars,nonbreaking,template,pagebreak",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Example content CSS (should be your site CSS)
        content_css : "<?= Router::url('/css/tiny_mce/content.css') ?>",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "<?= Router::url('/editor/template_list.js', true) ?>",
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
