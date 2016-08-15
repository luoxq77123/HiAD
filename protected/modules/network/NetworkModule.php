<?php

class NetworkModule extends CWebModule {

    public function init() {
        $this->setImport(array(
            'network.controllers.NBaseController'
        ));
    }

}

