<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yoophi
 * Date: 12. 12. 13.
 * Time: AM 10:27
 * To change this template use File | Settings | File Templates.
 */
App::uses('AppModel', 'Model');
class CardbookSpine extends AppModel {

    public $belongsTo = array('Cardbook');

}
?>
