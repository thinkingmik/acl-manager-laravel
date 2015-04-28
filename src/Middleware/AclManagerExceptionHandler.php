<?php

namespace ThinKingMik\AclManager\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Routing\Middleware;
use ThinKingMik\AclManager\Exceptions\AclException;

/*
* AclManagerExceptionHandler
*/
class AclManagerExceptionHandler implements Middleware
{
    /**
     * Register the AclManager error handlers
     * @return void
     */
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (AclException $ex) {
            if ($request->ajax() && $request->wantsJson()) {
                return new JsonResponse([
                    'error' => $ex->errorType,
                    'error_description' => $ex->getMessage()
                ], $ex->httpStatusCode, $ex->getHttpHeaders()
                );
            }

            return response()->view('acl-manager-laravel::acl_error', array(
                'header' => $ex->getHttpHeaders()[0],
                'code' => $ex->httpStatusCode,
                'error' => $ex->errorType,
                'message' => $ex->getMessage()
            ));
        }
    }
}
