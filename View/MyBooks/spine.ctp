<?php
/**
 * @var $this View
 */
?>
<h2>Spine</h2>

<table class="table table-striped" id="sort3">
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

    </tbody>
</table>

<div class="row-fluid">
    <?= $this->Html->link('글 추가', '#', array('class' => 'btn', 'id' => 'btn1')) ?>
    <a href="#" class="btn" id="btn-enable-sortable">순서 변경</a>
    <a href="#" class="btn ctl-btn" id="btn-apply-sort" style="display: none;">순서변경 적용</a>
    <a href="#" class="btn ctl-btn" id="btn-cancel-sort" style="display: none;">순서변경 취소</a>
</div>


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
        <a href="#" id="btn-modal-close" class="btn">Close</a>
        <a href="#" id="btn-spine-add" class="btn btn-primary">Save changes</a>
    </div>
</div>

<?php
$this->Html->script('jquery-ui-1.8.23.custom.min', false);
$this->Html->script('underscore', false);
?>
<script>
    var BASE_URL = '<?= $this->Html->url('/') ?>';
    var BOOK_ID = <?= $book['Book.id'] ?>;
</script>

<?php // echo $this->Html->script("spine.js"); ?>
<?php $this->Html->script('coffee-script', false); ?>
<script type="text/coffeescript" src="<?= $this->Html->url('/js/spine.coffee') ?>"></script>
