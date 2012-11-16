<?php
function printNodes($nodes, $depth = 0) {
    if (empty($nodes)) {
        return;
    }

    $attr = '';
    if ($depth == 0) {
        $attr = 'id="sortable"';
    }
    echo "<ol $attr>";
    foreach($nodes as $node) {
        $n = $node['Toc'];

        $editable = ($depth > 0) ?  true : false;
        echo sprintf('<li id="%s" data-name="%s" data-article_id="%d">',
                     'list_' . $n['id'], 
                     h($n['name']),
                     (($n['obj_type'] == 'article') ? $n['obj_id'] : 0)
                     );
        echo '<div>'
            .  sprintf('[#%d] %s %s', 
                    $n['id'],
                    sprintf('<span class="%s" data-id="%d">%s</span>',
                        ($editable ? 'editable' : ''),
                        $n['id'],
                        h($n['name'])
                    ),
                    sprintf(' <a href="#" class="deleteme" data-id="%d">DEL</a>',
                    $n['id']
                    )
                )
            . '</div>';
        printNodes($node['children'], ++$depth);

        /*
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
        */
    }
    echo '</ol>';
}

$book_id = $book['Book']['id'];
?>

<?
echo $this->Html->script('require-jquery.js', array(
    'data-main' => $this->Html->url('/js/main-sortable'),
    'inline' => false
    ));

$this->append('css');
printf('<link rel="stylesheet/less" type="text/css" href="%s" />', $this->Html->url('/css/nest-sortable.less'));
$this->end();
?>


<div class="row-fluid">
<div class="span4">

<h2><?php  echo __('Book');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($book['Book']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subject'); ?></dt>
		<dd>
			<?php echo h($book['Book']['subject']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($book['Book']['created']); ?>
			&nbsp;
		</dd>
	</dl>


</div>
<div class="span4">
<h2>TOC</h2>

<div class="toc">
<div style="padding: 1em; background-color: #eee; margin-bottom: 1em;">
<?php printNodes($tocs); ?>
</div>
</div>
</div>
<div class="span4">

<h2>Spine</h2>
<p>&nbsp;</p>






</div>
</div>


<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Book'), array('action' => 'edit', 'id' => $book['Book']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Book'), array('action' => 'destroy', 'id' => $book['Book']['id']), null, __('Are you sure you want to delete # %s?', $book['Book']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Books'), array('action' => 'index')); ?> </li>
	</ul>
</div>
