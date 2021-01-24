<?php

declare(strict_types=1);

namespace ByTIC\ORM\Repositories;

use ByTIC\ORM\Managers\EntityManagerInterface;

/**
 * Class Repository
 * @package ByTIC\ORM\Repositories
 */
class Repository implements RepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function find($id)
    {
        return $this->getEntityManager()->find($this->getEntityName(), $id);
    }

    /**
     * @inheritDoc
     */
    public function findAll()
    {
        return $this->findBy([]);
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        // TODO: Implement findBy() method.
    }

    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * @inheritDoc
     */
    public function getClassName()
    {
        return $this->getEntityName();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return '';
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
    }
}
