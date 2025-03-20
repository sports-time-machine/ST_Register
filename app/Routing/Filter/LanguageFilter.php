<?php
App::uses('DispatcherFilter', 'Routing');
App::uses('CakeSession', 'Model/Datasource');

class LanguageFilter extends DispatcherFilter {
    public function beforeDispatch(CakeEvent $event) {
        $request = $event->data['request'];
        $session = new CakeSession();
        $lang = $session->read('Config.language');

        if (!empty($request->query['lang'])) {
            $lang = $request->query['lang'];
            $session->write('Config.language', $lang);
            Configure::write('Config.language', $lang);
        } else {
            $lang = $session->read('Config.language');
            if ($lang) {
                Configure::write('Config.language', $lang);
            }
        }
    }
}
