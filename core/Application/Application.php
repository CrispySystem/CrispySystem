<?php

namespace CrispySystem\Application;

use CrispySystem\Container\Container;
use CrispySystem\Helpers\Config;
use CrispySystem\Router\Route;
use CrispySystem\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application extends Container
{
    const VERSION = '1.0.0';

    /**
     * Handles an HTTP request and returns the appropriate HTTP response
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $router = new Router();

        if ($router->match($request->getPathInfo(), $request->getMethod())) { // A match has been found
            /** @var Route $match */
            $match = $router->getMatch();

            // Check if the handler is a closure or not
            $handler = $match->getHandler();

            if (is_object($handler) && $handler instanceof \Closure) {
                try {
                    $content = $this->resolveFunction($handler);
                } catch (\Exception $e) {
                    if (DTAP === 'development') {
                        showPlainError('Something went wrong while resolving a closure, details below:', false);
                        pr($e);
                        exit;
                    }
                    return $this->respond(500);
                }
            } else {
                $controller = substr($handler, 0, stripos($handler, '.'));
                $method = substr($handler, (stripos($handler, '.') + 1));

                try {
                    $instance = $this->getInstance($controller);
                    $content = $this->resolveMethod($instance, $method, $match->getParameters());
                } catch (\Exception $e) {
                    if (DTAP === 'development') {
                        showPlainError('Something went wrong while resolving a controller, details below:', false);
                        pr($e);
                        exit;
                    }
                    return $this->respond(500);
                }
            }

            return $this->respond(200, $content);
        }

        return $this->respond(404);
    }

    private function respond(int $code, ?string $content = ''): Response
    {
        switch ($code) {
            case 404:
                $content = '<h1>404</h1>'; // TODO-PR1: Replace this with template
                break;
            case 500:
                $content = '<h1>500</h1>';
                break;
        }

        $response = new Response(
            $content,
            $code
        ); // TODO-PR2: Add a header type, text/html, text/css, etc.

        return $response;
    }
}
