<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

    Router::parseExtensions('html', 'json');
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	require_once(dirname(__FILE__) . '/routes-rest.php');

/**
 * My Articles
 */
    foreach(array('view', 'edit', 'destroy') as $action) {
        Router::connect("/my/articles/:id/$action/*", array('controller' => 'my_articles', 'action' => $action), array('id' => '[0-9]+'));
    }
	Router::connect('/my/articles/:id/*', array('controller' => 'my_articles', 'action' => 'view'), array('id' => '[0-9]+'));
	Router::connect('/my/articles/index/*', array('controller' => 'my_articles', 'action' => 'index'));
	Router::connect('/my/articles/add/*', array('controller' => 'my_articles', 'action' => 'add'));
	Router::connect('/my/articles/*', array('controller' => 'my_articles', 'action' => 'index'));

/**
 * My Cards
 */
    foreach(array('view', 'edit', 'destroy') as $action) {
        Router::connect("/my/cards/:id/$action/*", array('controller' => 'my_cards', 'action' => $action), array('id' => '[0-9]+'));
    }
	Router::connect('/my/cards/:id/*', array('controller' => 'my_cards', 'action' => 'view'), array('id' => '[0-9]+'));
	Router::connect('/my/cards/index/*', array('controller' => 'my_cards', 'action' => 'index'));
	Router::connect('/my/cards/add/*', array('controller' => 'my_cards', 'action' => 'add'));
	Router::connect('/my/cards/*', array('controller' => 'my_cards', 'action' => 'index'));

/**
 * My Books
 */
    foreach(array('view', 'edit', 'destroy', 'toc', 'toc_update', 'spine') as $action) {
        Router::connect("/my/books/:id/$action/*", array('controller' => 'my_books', 'action' => $action), array('id' => '[0-9]+'));
    }
	Router::connect('/my/books/:id/*', array('controller' => 'my_books', 'action' => 'view'), array('id' => '[0-9]+'));
	Router::connect('/my/books/index/*', array('controller' => 'my_books', 'action' => 'index'));
	Router::connect('/my/books/add/*', array('controller' => 'my_books', 'action' => 'add'));
	Router::connect('/my/books/*', array('controller' => 'my_books', 'action' => 'index'));

/**
 * My Cardbooks
 */
    foreach(array('view', 'edit', 'destroy', 'toc', 'toc_update', 'spine') as $action) {
        Router::connect("/my/cardbooks/:id/$action/*", array('controller' => 'my_cardbooks', 'action' => $action), array('id' => '[0-9]+'));
    }
	Router::connect('/my/cardbooks/:id/*', array('controller' => 'my_cardbooks', 'action' => 'view'), array('id' => '[0-9]+'));
	Router::connect('/my/cardbooks/index/*', array('controller' => 'my_cardbooks', 'action' => 'index'));
	Router::connect('/my/cardbooks/add/*', array('controller' => 'my_cardbooks', 'action' => 'add'));
	Router::connect('/my/cardbooks/*', array('controller' => 'my_cardbooks', 'action' => 'index'));

/**
 * My Photos
 */
    foreach(array('view', 'edit', 'destroy', 'toc', 'toc_update') as $action) {
        Router::connect("/my/photos/:id/$action/*", array('controller' => 'my_photos', 'action' => $action), array('id' => '[0-9]+'));
    }
	Router::connect('/my/photos/:id/*', array('controller' => 'my_photos', 'action' => 'view'), array('id' => '[0-9]+'));
	Router::connect('/my/photos/index/*', array('controller' => 'my_photos', 'action' => 'index'));
	Router::connect('/my/photos/simple/*', array('controller' => 'my_photos', 'action' => 'simple'));
	Router::connect('/my/photos/add/*', array('controller' => 'my_photos', 'action' => 'add'));
	Router::connect('/my/photos/*', array('controller' => 'my_photos', 'action' => 'index'));
/**
 * Books
	Router::connect('/books/:id', array('controller' => 'books', 'action' => 'view'), array('id' => '[0-9]+'));
 */

/**
 * Articles
 */
	Router::connect('/articles/:id', array('controller' => 'articles', 'action' => 'view'), array('id' => '[0-9]+'));
	Router::connect('/articles/:id/modify', array('controller' => 'articles', 'action' => 'modify'), array('id' => '[0-9]+'));

/**
 * My stuffs.
	Router::connect('/my/*', array('controller' => 'users'));
	Router::connect('/my/articles/*', array('controller' => 'my', 'action' => 'articles'));
 */
	//Router::connect('/my/article/:id', array('controller' => 'my', 'action' => 'article_view'), array('id' => '[0-9]+'));
	//Router::connect('/my/articles/:id', array('controller' => 'my', 'action' => 'article_view'), array('id' => '[0-9]+'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
