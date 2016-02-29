<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo __('CakePHP: the rapid development php framework:'); ?>
        <?php echo $title_for_layout; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css('bootstrap');
    ?>
    <style type="text/css">
        body { padding-top: 60px; }
    </style>
    <?php
    echo $this->Html->css('bootstrap-responsive');
    echo $this->Html->script('jquery-1.8.2.min');
    echo $this->Html->script('bootstrap');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">전자책 빌더 프로토타입</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="<?= $this->Html->url('/') ?>">Home</a></li>
                    <li><a href="<?= $this->Html->url('/my/books') ?>">E-Book</a></li>
                    <li><a href="<?= $this->Html->url('/my/cardbooks') ?>">Cardbook</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Resource <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?= $this->Html->url('/my/articles'); ?>">Articles</a></li>
                            <li><a href="<?= $this->Html->url('/my/cards'); ?>">Cards</a></li>
                            <li class="divider"></li>
                            <li><a href="<?= $this->Html->url('/my/photos'); ?>">Photos</a></li>
                            <li><a href="#">Videos</a></li>
                            <li><a href="#">Audios</a></li>
                            <li class="divider"></li>
                            <li class="nav-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                </ul>


                <div class="pull-right">

                    <? if (!$this->Session->check('Auth.User.id')): ?>
                    <form action="<?= Router::url('/users/login') ?>" method="post" class="navbar-form pull-right" _lpchecked="1">
                        <input class="span2" name="data[User][username]" type="text" placeholder="Email">
                        <input class="span2" name="data[User][password]" type="password" placeholder="Password">
                        <button type="submit" class="btn">Sign in</button>
                    </form>
                    <? else: ?>

                    <ul class="nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $this->Session->read('Auth.User.username') ?><b class="caret"></b></a>
                            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="nav-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>

                    </p>
                    <? endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="container" class="container">
    <div id="content">
        <?php echo $this->Session->flash(); ?>

        <?php echo $this->fetch('content'); ?>
    </div>
</div>

<?php echo $this->element('sql_dump'); ?>
</body>
</html>
