<?php

namespace CrispySystem\Router;

class Router
{
    /**
     * @var Router
     */
    protected static $instance;

    protected static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    /**
     * @var string
     */
    protected $pathPrefix = '';

    /**
     * @var string
     */
    protected $handlerPrefix = '';

    /**
     * @var string
     */
    protected $namePrefix = '';

    /**
     * @var Route
     */
    protected $match;

    public static function group()
    {
        static::$instance = new static();

        return static::$instance;
    }

    public static function getInstance(): Router
    {
        return static::$instance;
    }

    public function setPathPrefix(string $prefix)
    {
        $this->pathPrefix = $prefix;
        return $this;
    }

    public function getPathPrefix(): string
    {
        return $this->pathPrefix;
    }

    public function setHandlerPrefix(string $prefix)
    {
        $this->handlerPrefix = $prefix;

        return $this;
    }

    public function getHandlerPrefix(): string
    {
        return $this->handlerPrefix;
    }

    public function setNamePrefix(string $namePrefix)
    {
        $this->namePrefix = $namePrefix;

        return $this;
    }

    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }

    public function routes(\Closure $closure)
    {
        call_user_func($closure);
    }

    public static function addRoute(string $verb, Route $route)
    {
        static::$routes[strtoupper($verb)][] = $route;
    }

    public static function getRoutes(): array
    {
        return static::$routes;
    }

    public static function getRoutesByMethod(string $verb): array
    {
        return static::$routes[strtoupper($verb)];
    }

    public static function getRouteByName(string $name): ?Route
    {
        foreach (static::$routes as $routes) {
            /** @var Route $route */
            foreach ($routes as $route) {
                if ($route->getName() === $name) {
                    return $route;
                }
            }
        }

        return null;
    }

    public function match(string $path, string $method): bool
    {
        /** @var Route $route */
        foreach (static::getRoutesByMethod($method) as $route) {
            $route->createRegex();
            if ($route->isMatch($path)) {
                $this->match = $route;
                return true;
            }
        }
        return false;
    }

    public function getMatch(): Route
    {
        return $this->match;
    }
}