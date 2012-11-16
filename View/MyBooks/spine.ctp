<h2>Spine</h2>

<table cellpadding="0" cellspacing="0" class="table table-striped" id="sort3">
<thead>
<tr>
<th>id</th>
<th>article_id</th>
<th>subject</th>
<th>created</th>
<th>spine.order</th>
<th>-</th>
</tr>
</thead>
<tbody>
<?php 
foreach ($spine as $item):
    $item = Set::flatten($item);
    $article_id = $item['Article.id'];
?>
<tr>
<td><?= $item['BookSpine.id'] ?></td>
<td><?= $item['Article.id'] ?></td>
<td><?= $this->Html->link($item['Article.subject'], '/my/articles/' . $article_id . '/view'); ?></td>
<td><?= $item['Article.created'] ?></td>
<td><?= $item['BookSpine.order'] ?></td>
<td>
<?= $this->Html->link('<i class="icon-trash"></i>', '#', array('class' => 'btn btn-danger btn-mini', 'escape' => false)) ?>
</td>
</tr>
<?php 
endforeach;
?>
</tbody>
</table>

<div class="row-fluid">
<?= $this->Html->link('글 추가', '#', array('class' => 'btn', 'id' => 'btn1')) ?>
<?= $this->Html->link('순서 변경', '#', array('class' => 'btn')) ?>
</div>

<?php
// $this->Html->script('http://code.jquery.com/ui/1.8.18/jquery-ui.min.js', false);
$this->Html->script('jquery-ui-1.8.23.custom.min', false);
$this->Html->script('underscore', false);
?>
<script>
// Return a helper with preserved width of cells
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};

/*
$("#sort tbody").sortable({
    helper: fixHelper
}).disableSelection();

var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
      $(this).width($originals.eq(index).width())
    });
    return $helper;
};

$("#sort2 tbody").sortable({
    helper: fixHelperModified 
    
}).disableSelection();
*/


$(document).ready(function() {
$("#sort3 tbody").sortable({
    helper: fixHelper
}).disableSelection();
// $("#sort3 tbody").sortable().disableSelection();
});
</script>


<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">

<table cellpadding="0" cellspacing="0" class="table table-striped" id="article_list">
<thead>
<tr>
<th>&nbsp;</th>
<th>id</th>
<th>category</th>
<th>subject</th>
<th>created</th>
</tr>
</thead>
<tbody>
</tbody>
</table>


  </div>
  <div class="modal-footer">
    <a href="#" class="btn">Close</a>
    <a href="#" class="btn btn-primary">Save changes</a>
  </div>
</div>

<script>
$('#btn1').click(function() {
    $('#myModal').modal({ keyboard: false });

    var rootURL = '<?= $this->Html->url('/') ?>';
    $.ajax({
        type: 'GET',
        url: rootURL + 'my/articles.json',
        dataType: 'json',
        success: function(r) { 
            console.log(r); 
            var compiled = _.template("<tr>" +
                "<td><input type=\"checkbox\" /></td>" +
                "<td><%= id %></td><td><%= category_id %></td><td><%= subject %></td><td><%= created %></tr>");
            $.each(r, function(index, value) {
                var html = compiled(value);
                $('#article_list tbody').append(html);
            });
        }
    })

});
</script>
