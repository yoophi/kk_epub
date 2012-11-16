<?php
define('PLUGIN_DIR', dirname(dirname(dirname(__FILE__))));
define('BOOTSTRAP_DIR', join(DS, array(PLUGIN_DIR, 'Vendor', 'bootstrap')));

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class BootstrapShell extends AppShell {

	function main() {
		pr(__METHOD__);
	}

	function copy() {
		foreach(array('css' => CSS, 'js' => JS, 'img' => IMAGES) as $src => $target) {
			$src = BOOTSTRAP_DIR . DS . 'bootstrap' . DS . $src . DS;
			$folder = new Folder($src);
			foreach($folder->find('.*') as $filename) {
				$file = new File($src . $filename);

				$this->out(sprintf('copying %s to %s ...', $src . $filename, $target . $filename));
				if ($file->copy($target . $filename, true)) {
					$this->out('... OK');
				} else {
					$this->out('... ERROR');
				}
			}
		}
	}

}
