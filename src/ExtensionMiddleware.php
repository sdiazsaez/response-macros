<?php

namespace Larangular\ResponseMacros;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Contracts\Routing\Registrar;
use Larangular\Support\Instance;

class ExtensionMiddleware {

    protected $router;

    /**
     * Create a new bindings substitutor.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar $router
     * @return void
     */
    public function __construct(Registrar $router) {
        $this->router = $router;
    }

    public function handle(Request $request, Closure $next) {

        $response = $next($request);
        if (isset($request->extension)) {
            $extension = str_replace('.', '', $request->extension);

            switch ($extension) {
                case ExtensionsEnum::XML:

                    return response()->xml($this->getContentResponse($response));
                    break;
                case ExtensionsEnum::XLSX:
                    return response()->xlsx($this->getContentResponse($response));
                    break;
                case ExtensionsEnum::JSON:
                default:
                    return $response;
                    break;
            }
        }

        return $response;
    }

    private function getContentResponse($response): array {
        return Instance::instanceOf($response->original, Collection::class)
            ? $response->original->toArray()
            : $response->original;
    }
}
