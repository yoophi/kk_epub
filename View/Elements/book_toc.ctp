<?php
if (!function_exists('printToc')) {
    function printToc($tocs) {
        if (empty($tocs)) {
            return;
        }

        echo '<ul>';
        foreach($tocs as $toc) {
            $toc_item = $toc['Toc'];
            $toc_id = $toc_item['id'];

            echo '<li>';
            echo $toc_item['name'];
            if ($toc_item['obj_type'] == 'article') {
                $article_id = $toc_item['obj_id'];
                if (empty($article_id)) {
                    printf(' / <a href="%s">+</a>', Router::url('/books/toc_link_article/toc_id:' . $toc_id));
                } else {
                    printf(' / <a href="%s">E</a>', Router::url('/articles/edit/' . $article_id));
                }
            }
            printToc($toc['children']);
            echo '</li>';
        }
        echo '</ul>';
    }
}
?>
<h2>Book TOC</h2>

<?php printToc($current_book_toc); ?>
