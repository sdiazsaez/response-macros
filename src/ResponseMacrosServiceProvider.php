<?php

namespace Larangular\ResponseMacros;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Larangular\Support\Enum;

class ResponseMacrosServiceProvider extends ServiceProvider {

    public function boot() {
        $extensions = Enum::getConstants(ExtensionsEnum::class);
        $pattern = '\.(' . implode('|', $extensions) . ')';
        Route::pattern('extension', $pattern);
    }

    public function register() {
        $this->app->register('Cyberduck\LaravelExcel\ExcelServiceProvider');
        $this->app->register('Mtownsend\ResponseXml\Providers\ResponseXmlServiceProvider');
        $this->app->make('Larangular\ResponseMacros\ResponseMacros');
    }

}
