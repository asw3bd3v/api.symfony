<?php

namespace App\Service\ExceptionHandler;

class ExceptionMappingResolver
{
    const DEFAULT_HIDDEN = true;
    const DEFAULT_LOGGABLE = false;
    /**
     * @var ExceptionMapping[]
     */
    private array $mappings = [];

    public function __construct(array $mappings)
    {
        foreach ($mappings as $class => $mapping) {
            if (empty($mapping['code'])) {
                throw new \InvalidArgumentException('Code is mandatory for class ' . $class);
            }

            $this->addMapping(
                $class,
                $mapping['code'],
                $mapping['hidden'] ?? self::DEFAULT_HIDDEN,
                $mapping['loggable'] ?? self::DEFAULT_LOGGABLE,
            );
        }
    }

    public function resolve(string $throwableClass): ?ExceptionMapping
    {
        $foundMapping = null;

        foreach ($this->mappings as $class => $mapping) {
            if ($throwableClass === $class || is_subclass_of($throwableClass, $class)) {
                $foundMapping = $mapping;
                break;
            }
        }

        return $foundMapping;
    }

    private function addMapping(string $class, int $code, bool $hidden, bool $loggale)
    {
        $this->mappings[$class] = new ExceptionMapping($code, $hidden, $loggale);
    }
}
