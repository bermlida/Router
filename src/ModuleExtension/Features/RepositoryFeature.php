<?php

namespace ModuleExtension\Features;

use ModuleExtension\Constraints\EntityConstraint;

trait RepositoryFeature
{
    protected $entity;

    public function __construct(EntityConstraint $entity)
    {
        $this->entity = $entity;
    }

    public function create(array $data)
    {
        return $this->entity->create($data);
    }

    public function delete(int $id)
    {
        return $this->entity->delete($id);
    }

    public function update(array $data, int $id)
    {
        return $this->entity->update($data, $id);
    }

    public function get(int $id)
    {
        return $this->entity->get($id);
    }
    
    public function read()
    {
        return $this->entity->read();
    }
}