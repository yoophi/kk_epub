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
    $depth++;
    foreach($nodes as $node) {
        echo '<li id="list_' . $node['Category']['id'] . '">';
        echo '<div>'
            .  sprintf('[#%d %d/%d] %s', 
                    $node['Category']['id'],
                    $node['Category']['lft'],
                    $node['Category']['rght'],
                    $node['Category']['name'])
            . '</div>';
        printNodes($node['children'], $depth);
    }
    echo '</ol>';
}
?>
<?php
$this->Html->script('jquery-1.8.1.min', false);
$this->Html->script('underscore-min', false);
$this->Html->script('jquery-ui-1.8.23.custom.min', false);
$this->Html->script('jquery.mjs.nestedSortable', false);
$this->Html->script('json2', false);
?>
<style type="text/css">
ol {
margin: 0;
padding: 0;
padding-left: 30px;
}

ol.sortable, ol.sortable ol {
margin: 0 0 0 25px;
padding: 0;
list-style-type: none;
}

ol.sortable {
margin: 0 0;
}

.sortable li {
margin: 5px 0 0 0;
padding: 0;
}

.sortable li div  {
border: 1px solid #d4d4d4;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
border-color: #D4D4D4 #D4D4D4 #BCBCBC;
padding: 6px;
margin: 0;
cursor: move;
background: #f6f6f6;
background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
background: linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );

}
</style>
<h2>Categories</h2>
<div>
<?php printNodes($nodes); ?>
</div>
<div>
<a id="to_array" href="#">to array</a>
</div>
<pre id="toArrayOutput"></pre>

<?php
echo $this->Form->create('Category', array('action' => 'update', 'id' => 'UpdateJson'));
echo $this->Form->input('json', array('type' => 'text', 'value' => '', 'id' => 'txt-update-json'));
echo $this->Form->submit('Update()');
echo $this->Form->end();
?>

<script>
$(document).ready(function(){
    $('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div'
    });
    $('#to_array').click(function(e) {
        arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
        console.log(JSON.stringify(arraied));

        var data = new Array();
        _.each(arraied, function(element, n) {
            if (n == 0) { return; }
            element.id = element.item_id;
            element.left--;
            element.right--;
            element.lft = element.left;
            element.rght = element.right;
            delete element.item_id;
            delete element.lft;
            delete element.rght;
            delete element.depth;

            data[data.length] = element;
        });

        json_string = JSON.stringify(data);
        $('#toArrayOutput').html(json_string);
        $('#txt-update-json').val(json_string);



        // arraied = dump(arraied);
        // (typeof($('#toArrayOutput')[0].textContent) != 'undefined') ?
        // $('#toArrayOutput')[0].textContent = arraied : $('#toArrayOutput')[0].innerText = arraied;

        });
    });

function dump(arr,level) {
    var dumped_text = "";
    if(!level) level = 0;

    //The padding given at the beginning of the line.
    var level_padding = "";
    for(var j=0;j<level+1;j++) level_padding += "    ";

    if(typeof(arr) == 'object') { //Array/Hashes/Objects
        for(var item in arr) {
            var value = arr[item];

            if(typeof(value) == 'object') { //If it is an array,
                dumped_text += level_padding + "'" + item + "' ...\n";
                dumped_text += dump(value,level+1);
            } else {
                dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
            }
        }
    } else { //Strings/Chars/Numbers etc.
        dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
    }
    return dumped_text;
}

</script>
