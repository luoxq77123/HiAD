<?php
    $this->widget('AdPositionListWidget', array(
        'rote'=>'appAd/getAdPositionList','arrPageSize' => array(3 => 3, 10 => 10, 20 => 20), 'adTypeId' => 2
    ));
?>