<?php

namespace CrispySystem\Router;

class Route
{
    /**
     * @var Router
     */
    protected static $routerInstance;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string|\Closure
     */
    protected $handler;

    /**
     * @var string
     */
    protected $name;

    /**
     * List of rules for parameters in the route path
     * @var array
     */
    protected $rules = [];

    /**
     * @var string
     */
    protected $regex;

    protected $parameterNames = [];

    protected $parameters = [];

    public function __construct(Router $router, string $verb, string $path, $handler)
    {
        $this->router = $router;
        $this->path = $this->router->getPathPrefix() . $path;
        if (!is_callable($handler)) {
            $this->handler = $this->router->getHandlerPrefix() . $handler;
        } else {
            $this->handler = $handler;
        }
    }

    public static function get(string $path, $handler)
    {
        return static::add('GET', $path, $handler);
    }

    public static function post(string $path, $handler)
    {
        return static::add('POST', $path, $handler);
    }

    public static function put(string $path, $handler)
    {
        return static::add('PUT', $path, $handler);
    }

    public static function patch(string $path, $handler)
    {
        return static::add('PATCH', $path, $handler);
    }

    public static function delete(string $path, $handler)
    {
        return static::add('DELETE', $path, $handler);
    }

    protected static function add(string $verb, string $path, $handler)
    {
        /** @var Router $router */
        $router = static::getRouterInstance();

        $route = new static($router, $verb, $path, $handler);

        Router::addRoute($verb, $route);

        return $route;
    }

    public function where(string $parameter, string $regex): Route
    {
        $this->rules[$parameter] = $regex;

        return $this;
    }

    public function setName(string $name, bool $override = false): Route
    {
        if ($override) {
            $this->name = $name;
            return $this;
        }
        $this->name = $this->router->getNamePrefix() . '.' . $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function createRegex(): void
    {
        $parts = explode('/', ltrim($this->path, '/'));
        $regex = '/^'; // Matches the start of the string
        foreach ($parts as $part) {
            $regex .= '\/'; // Matches starting slash
            // Handle non-variable parts
            if (stripos($part, '{') === false &&
                stripos($part, '}') === false) {
                $regex .= $part; // Exactly match this part
                continue;
            }
            // Handle variable parts
            $name = str_ireplace(['{', '}'], '', $part);
            $this->parameterNames[] = $name;
            $this->parameters[$name] = null;
            // Check if a rule exists for this part
            if (isset($this->rules[$name])) {
                $regex .= $this->rules[$name];
                continue;
            }
            // No rule exists, match all except slashes
            $regex .= '([^\/]+)'; // TODO : SL : 28/09/2017 @ 16:49 > Might be to broad, review this
        }
        $regex .= '$/'; // Matches end of string
        $this->regex = $regex;
    }

    public function isMatch(string $path): bool
    {
        if (preg_match_all($this->regex, $path, $matches) !== 0 &&
            !empty($matches) &&
            !empty($matches[0])) {
            array_shift($matches);
            foreach ($matches as $i => $match) {
                $this->parameters[$this->parameterNames[$i]] = $match[0];
            }
            return true;
        }
        return false; // Match not found
    }

    protected static function getRouterInstance(): Router
    {
        static::$routerInstance = Router::getInstance();

        return static::$routerInstance;
    }
}
