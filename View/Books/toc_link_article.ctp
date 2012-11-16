<div class="toc">
<h2>목차 - 글 연결</h2>

<p><strong><?= h($toc['Toc']['name']) ?></strong>에 해당하는 글을 연결합니다.</p>

<?php echo $this->Form->create('Toc', array('url' => '/books/toc_link_article'));?>
	<fieldset>
		<legend><?php echo __('Toc/Article Link'); ?></legend>
	<?php
		echo $this->Form->input('action', array('type' => 'radio',
                    'options' => array('ADD' => '새로 작성', 'LINK' => '기존 글 추가'),
                    'class' => 'radio'));
    ?>
    <div id="form-link" style="display: none;">
    <?
		echo $this->Form->input('id', array('type' => 'text', 'value' => $toc_id));
		echo $this->Form->input('article_id', array('type' => 'text'));
	?>
    </div>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

<script language="javascript">
$(document).ready(function() {
    $('input.radio').click(function() {
        if ($('input.radio:checked').val() == 'LINK') {
            $('#form-link').show();
        } else {
            $('#form-link').hide();
        }
    });
});
</script>
