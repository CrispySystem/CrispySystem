<?php

namespace CrispySystem\Container;

class Container
{
    /**
     * @var array Stores list of instances
     */
    private $instances = [];

    /**
     * Gets an existing instance, or creates a new one and stores it if it doesn't yet exist
     * @param string $class Class name to build
     * @return object Returns the instance
     */
    public function getInstance(string $class)
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }
        return $this->createInstance($class);
    }

    /**
     * Create a new instance of a class
     * @param string $class Class name to build
     * @return object Creates a new instance and returns it
     */
    public function createInstance(string $class)
    {
        $reflection = new \ReflectionClass($class);

        // If the constructor has no parameters, there is no need to resolve them
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            $this->instances[$class] = new $class;
            return new $class;
        }

        // Resolve the dependencies recursively
        $dependencies = $constructor->getParameters();
        $instances = $this->resolveDependencies($dependencies);

        $o = $reflection->newInstanceArgs($instances);
        $this->instances[$class] = $o;
        return $o;
    }

    /**
     * Resolves dependencies recursively
     * @param array $dependencies List of dependencies
     * @param array $parameters Fill parameter slots with pre-given data
     * @return array The resolved dependencies
     */
    protected function resolveDependencies(array $dependencies, array $parameters = [])
    {
        $results = [];
        foreach ($dependencies as $dependency) {
            if (array_key_exists($dependency->name, $parameters)) { // Parameter value is given
                $results[] = $parameters[$dependency->name];
            } else { // Parameter value is not given
                $results[] = is_null($class = $dependency->getClass())
                    ? $dependency->getDefaultValue()
                    : $this->getInstance($dependency->getClass()->name);
            }
        }
        return $results;
    }

    /**
     * Calls the method of an instance after resolving dependencies
     * @param object $instance Instance to use when calling method
     * @param string $method Name of the method
     * @param array $parameters Pre-given data to fill slots that have no default value
     * @return mixed Value returned in method
     */
    public function resolveMethod($instance, string $method, array $parameters = [])
    {
        $name = get_class($instance);
        $reflection = new \ReflectionMethod($name, $method);
        $dependencies = $reflection->getParameters();
        $instances = $this->resolveDependencies($dependencies, $parameters); // Actually resolve the dependencies
        $o = $reflection->invokeArgs($instance, $instances);
        return $o;
    }

    /**
     * Resolves the dependencies of a function
     * @param \Closure $closure The closure to resolve
     * @return mixed Value returned in method
     */
    public function resolveFunction(\Closure $closure)
    {
        $reflection = new \ReflectionFunction($closure);
        $dependencies = $reflection->getParameters();
        $instances = $this->resolveDependencies($dependencies); // Actually resolve the dependencies
        $o = $reflection->invokeArgs($instances);
        return $o;
    }
}
