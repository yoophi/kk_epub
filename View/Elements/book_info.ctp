<h2>Book Info</h2>

<?php //pr($current_book_info); ?>
<h3><?= sprintf("<a href=\"%s\">%s</a>", $this->Html->url('/books/view/' . $current_book_info['Book.id']), h($current_book_info['Book.subject'])) ?></h3>
<ul>
<li><?= date('Y/m/d', strtotime($current_book_info['Book.created'])) ?></li>
<li>Created by: <?= sprintf("<a href=\"mailto:%s\">%s</a>",  $current_book_info['User.email'], h($current_book_info['User.username'])) ?></li>
</ul>
