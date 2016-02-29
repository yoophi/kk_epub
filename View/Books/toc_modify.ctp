<?php
function printNodes($nodes, $depth = 0) {
    if (empty($nodes)) {
        return;
    }

    $class = '';
    if ($depth == 0) {
        $class = 'class="sortable"';
    }
    echo "<ol $class>";
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
    }
    echo '</ol>';
}

$this->Html->script('jquery-1.8.1.min', false);
$this->Html->script('underscore-min', false);
$this->Html->script('jquery-ui-1.8.23.custom.min', false);
$this->Html->script('jquery.mjs.nestedSortable', false);
$this->Html->script('json2', false);
$this->Html->script('http://www.appelsiini.net/download/jquery.jeditable.js', false);
?>
<style type="text/css">
div.content-block span.editable form {
    display: inline;
}
div.content-block span.editable input {
    display: inline !important;
    width: 10em !important;
}
</style>

<?php
$this->append('css');
printf('<link rel="stylesheet/less" type="text/css" href="%s" />', $this->Html->url('/css/nest-sortable.less'));
$this->end();
?>
<h2><?= h($cardbook['subject']) ?></h2>
<div>
<?php printNodes($toc_items); ?>
</div>
<div>
<a id="to_array" href="#">to array</a> | 
<a id="add_item" href="#">add item</a>
</div>

<?php
echo $this->Form->create('Toc', array('url' => '/books/toc_update/' . $cardbook['id'], 'id' => 'UpdateJson'));
echo $this->Form->input('json', array('type' => 'text', 'value' => '', 'id' => 'txt-update-json'));
echo $this->Form->submit('Update()');
echo $this->Form->end();
?>

<script>
$(document).ready(function(){

    var getTocJson = function(e) {
        arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
        //console.log(JSON.stringify(arraied));

        var data = new Array();
        var order = [];
        _.each(arraied, function(element, n) {
            if (n == 0) { return; }

            //console.log(JSON.stringify(element));
            element.id = element.item_id;
            element.name = $('#list_' + element.id).attr('data-name');
            element.article_id = $('#list_' + element.id).attr('data-article_id');
            if (typeof order[element.depth] == 'undefined') {
                order[element.depth] = 1;
            } else {
                order[element.depth]++;
            }
            element.order = order[element.depth];

            //console.log(JSON.stringify(element));
            //console.log('-----');
            data[data.length] = element;
        });

        json_string = JSON.stringify(data);
        //$('#toArrayOutput').html(json_string);
        $('#txt-update-json').val(json_string);

        return true;
    }

    $('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        protectRoot: true,
        toleranceElement: '> div'
    });

    $('.deleteme').click(function() {
        var obj_id = $(this).attr('data-id');
        $('#list_' + obj_id).remove();
    });

    $('#to_array').click(getTocJson);

    $('#UpdateJson').submit(function() {
        return getTocJson();
    });


    $('#add_item').click(function() {
        var new_id = _.uniqueId() + 1000;
        $('ol.sortable > li > ol').append(
            '<li id="list_' + new_id + '" data-article_id="0" data-name="undefined">'
            + '<div>'
                + '<span class="editable" data-id="' + new_id + '">new toc item (' + new_id + ')</span>'
                + '<a href="#" class="deleteme" data-id="' + new_id + '">DEL</a>'
            + '</div>'
            + '</li>');

        $('#list_' + new_id).find('.editable').editable(
            function(value, settings) {
                var id = $(this).attr('data-id');
                $('#list_' + id).attr('data-name', value);
                return value;
            }, {
                type : 'text', 
                submit : 'OK'
            } 
        );

        $('#list_' + new_id).find('.deleteme').click(function() {
            var obj_id = $(this).attr('data-id');
            $('#list_' + obj_id).remove();
        });
    });

    $('.editable').editable(function(value, settings) {
            var id = $(this).attr('data-id');
            $('#list_' + id).attr('data-name', value);
            return value;
        }, {
            type : 'text', 
            submit : 'OK'
        }
    );
});
</script>

<?php
echo $this->Form->create('Toc', array('url' => '/books/toc_add'));
echo $this->Form->input('book_id', array('type' => 'text', 'value' => $cardbook['id']));
echo $this->Form->input('parent_id', array('type' => 'text'));
echo $this->Form->input('name', array('type' => 'text'));
echo $this->Form->submit('Submit()');
?>
