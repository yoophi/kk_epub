<?php
$book_id = $cardbook['Book']['id'];

function printToc($tocs) {
    if (empty($tocs)) {
        return;
    }

    echo '<ul>';
    foreach($tocs as $toc) {
        $toc_item = $toc['Toc'];
        $toc_id = $toc_item['id'];

        echo '<li>';
        if ($toc_item['obj_type'] == 'article') {
            $article_id = $toc_item['obj_id'];
            if (empty($article_id)) {
                echo $toc_item['name'];
                printf(' / <a href="%s">add</a>', Router::url('/books/toc_link_article/toc_id:' . $toc_id));
            } else {
                printf('<a href="%s">%s</a>', Router::url('/articles/edit/' . $article_id), $toc_item['name']);
                printf(' / <a href="%s">unlink</a>', Router::url('/books/toc_unlink_article/toc_id:' . $toc_id));
            }
            printf(' / <a href="%s">remove</a>', Router::url('/books/toc_remove/toc_id:' . $toc_id));
        } else {
            // book
            echo '<strong>' . $toc_item['name'] . '</strong>';
        }
        printToc($toc['children']);
        echo '</li>';
    }
    echo '</ul>';
}
?>
<h2>TOC</h2>

<div class="toc">
<div style="padding: 1em; background-color: #eee; margin-bottom: 1em;">
<?php printToc($tocs); ?>
</div>

<p><?= $this->Html->link('목차 순서 수정', '/books/toc_modify/' . $book_id); ?></p>

<?php echo $this->Form->create('Toc', array('url' => '/books/toc_add'));?>
	<fieldset>
		<legend><?php echo __('Add Toc'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('parent_id', array('type' => 'select', 'options' => $parent_ids));
		echo $this->Form->input('book_id', array('type' => 'text', 'value' => $book_id));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>

