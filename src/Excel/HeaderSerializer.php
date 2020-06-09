<?php

namespace Larangular\ResponseMacros\Excel;

use Cyberduck\LaravelExcel\Serialiser\BasicSerialiser;

class HeaderSerializer extends BasicSerialiser {

    private $header;
    private $headerKeys;

    public function __construct($header = []) {
        $this->header = $header;
        $this->headerKeys = array_fill_keys($this->header, null);
    }

    public function getData($data) {
        $data = parent::getData($data);
        return array_merge($this->headerKeys, $data);
    }

    public function getHeaderRow() {
        return $this->header;
    }
}
