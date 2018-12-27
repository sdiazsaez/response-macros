<?php

namespace Larangular\ResponseMacros\Excel;

use Cyberduck\LaravelExcel\Serialiser\BasicSerialiser;

class HeaderSerializer extends BasicSerialiser {

    private $header;

    public function __construct($header = []) {
        $this->header = $header;
    }

    public function getData($data) {
        return parent::getData($data);
    }

    public function getHeaderRow() {
        return $this->header;
    }
}
