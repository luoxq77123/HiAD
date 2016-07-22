<?php

class InterfaceModule extends CWebModule {

    public function init() {
        $this->setImport(array(
            'interface.controllers.IBaseController'
        ));
    }

}

