<?php
App::uses('AppController', 'Controller');
class SettingsController extends AppController {
    public $uses = array();

    public function index() {
        $resp = new CakeResponse();
        $resp->type('application/javascript');
        $body = sprintf('define(function() { return %s; });', json_encode(array('uploader_url' => Configure::read("Site.MU_URL"))));
        $resp->body($body);
        return $resp;
    }

}
