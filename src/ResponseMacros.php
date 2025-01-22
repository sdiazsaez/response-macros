<?php

namespace Larangular\ResponseMacros;

use Illuminate\Contracts\Routing\ResponseFactory;
use Larangular\ResponseMacros\Macros\{CSV, XML, XLSX};

class ResponseMacros {
    /**
     * Macros.
     * @var array
     */
    protected $macros = [
        XML::class,
        XLSX::class,
        CSV::class
    ];

    /**
     * Constructor.
     * @param ResponseFactory $factory
     */
    public function __construct(ResponseFactory $factory) {
        $this->bindMacros($factory);
    }

    /**
     * Bind macros.
     * @param  ResponseFactory $factory
     * @return void
     */
    public function bindMacros($factory) {
        foreach ($this->macros as $macro) {
            (new $macro)->run($factory);
        }
    }
}
