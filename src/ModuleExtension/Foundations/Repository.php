<?php

namespace ModuleExtension\Foundations;

use ReflectionClass;
use ReflectionProperty;
use ModuleExtension\Features\RepositoryFeature;

abstract class Repository
{
    public function __construct()
    {
        $class = new ReflectionClass($this);
        $properties = $class->getProperties(ReflectionProperty::IS_PRIVATE);
        
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $property_name = $property->getName();
            $property_value = $property->getValue($this);

            if (!is_string($property_value)) {
                $segments = explode("_", $property_name);
                if (count($segments) > 1) {
                    $callback = function ($name) {
                        return ucfirst(strtolower($name));
                    };
                }  else {
                    $callback = "ucfirst";
                }
                $property_value = implode(array_map($callback, $segments));
            }

            $entity_constraint = "ModuleExtension\\Constraints\\EntityConstraint";
            $namespace = (strpos($property_value, "\\") === false) ? $class->getNamespaceName() . "\\Entities\\" : "";
            $property_value = $namespace . $property_value;
            if ((new ReflectionClass($property_value))->implementsInterface($entity_constraint)) {
                $property->setValue($this, new $property_value);
            }
        }
    }

    public function offsetSet($offset, $value)
    {
        //
    }

    public function offsetUnset($offset)
    {
        //
    }

    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        $class = new ReflectionClass($this);
        if ($class->hasProperty($name)) {
            $property = $class->getProperty($name);
            
            if ($property->isPrivate()) {
                $property->setAccessible(true);
                $property_value = $property->getValue($this);
                
                if (is_object($property_value)) {
                    $entity_constraint = "ModuleExtension\\Constraints\\EntityConstraint";
                    if ((new ReflectionClass($property_value))->implementsInterface($entity_constraint)) {
                        return new class($property_value) {
                            use RepositoryFeature;
                        };
                    }
                }
            }
        }
        return null;
    }

    public function __get($name) 
    {
        return $this->offsetGet($name);
    }
}