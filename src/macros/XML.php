<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018-12-08
 */

namespace Larangular\ResponseMacros\Macros;

use Larangular\ResponseMacros\ResponseMacroInterface;
use Illuminate\Support\Facades\Response;

class XML implements ResponseMacroInterface {

    public function run($factory) {
        $factory->macro('xml', function (array $vars, $status = 200, array $header = [], $xml = null) {
            if (is_null($xml)) {
                $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');
            }
            foreach ($vars as $key => $value) {
                if(is_numeric($key)) {
                    $key = 'item-'.$key;
                }
                if (is_array($value)) {
                    //XML::makeResponse($value, $status, $header, $xml->addChild($key));
                    Response::xml($value, $status, $header, $xml->addChild($key));
                } else {

                    $xml->addChild($key, $value);
                }
            }
            if (empty($header)) {
                $header['Content-Type'] = 'application/xml';
            }
            return Response::make($xml->asXML(), $status, $header);
        });
    }

    public static function isAssoc(array $arr): bool {
        if ([] === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }


}
