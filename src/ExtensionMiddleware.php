<?php

namespace Larangular\ResponseMacros;

use Closure;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Larangular\Support\Instance;

class ExtensionMiddleware {

    protected $router;

    /**
     * Create a new bindings substitutor.
     *
     * @param \Illuminate\Contracts\Routing\Registrar $router
     * @return void
     */
    public function __construct(Registrar $router) {
        $this->router = $router;
    }

    public function handle(Request $request, Closure $next) {
        $extension = '';
        if ($request->filled('rm_extension')) {
            $extension = str_replace('.', '', $request->rm_extension);
            $request->request->remove('rm_extension');
        }

        $response = $next($request);
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

    private function getContentResponse($response): array {
        $json = Instance::instanceOf($response->original, Collection::class)
            ? $response->original->toJson()
            : json_encode($response->original);

        return json_decode($json, true);
    }
}
