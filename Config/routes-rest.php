<?php
// My Photos

Router::connect('/my/photos', array('controller' => 'my_photos', 'action' => 'index',  '[method]' => 'GET'));
Router::connect('/my/photos', array('controller' => 'my_photos', 'action' => 'add',    '[method]' => 'POST'));
Router::connect('/my/photos/:id', array('controller' => 'my_photos', 'action' => 'view', '[method]' => 'GET'),  array('id' => '[0-9]+'));
Router::connect('/my/photos/:id', array('controller' => 'my_photos', 'action' => 'edit', '[method]' => 'PUT'),  array('id' => '[0-9]+'));
Router::connect('/my/photos/:id', array('controller' => 'my_photos', 'action' => 'edit', '[method]' => 'POST'), array('id' => '[0-9]+'));
Router::connect('/my/photos/:id', array('controller' => 'my_photos', 'action' => 'delete', '[method]' => 'DELETE'), array('id' => '[0-9]+'));

