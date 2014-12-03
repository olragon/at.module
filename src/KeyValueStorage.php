<?php

namespace Drupal\at;

use Doctrine\KeyValueStore\NotFoundException;
use Doctrine\KeyValueStore\Storage\Storage;
use Drupal\at\Drupal\DrupalDatabaseAPI;

class KeyValueStorage implements Storage
{

    /** @var DrupalDatabaseAPI */
    private $api;

    /** @var string */
    private $table;

    /** @var string */
    private $collectionColumn;

    /** @var string */
    private $keyColumn;

    /** @var string */
    private $valueColumn;

    public function __construct(DrupalDatabaseAPI $api, $table = 'at_kv', $collectionColumn = 'collection', $keyColumn = 'k', $valueColumn = 'v')
    {
        $this->api = $api;
        $this->table = $table;
        $this->collectionColumn = $collectionColumn;
        $this->keyColumn = $keyColumn;
        $this->valueColumn = $valueColumn;
    }

    /**
     * Return a name of the underlying storage.
     * @return string
     */
    public function getName()
    {
        return 'drupal_dbal';
    }

    public function supportsPartialUpdates()
    {
        return false;
    }

    public function supportsCompositePrimaryKeys()
    {
        return false;
    }

    public function requiresCompositePrimaryKeys()
    {
        return false;
    }

    public function insert($collectionName, $key, array $data)
    {
        $this->api->insert($this->table)
            ->fields(array(
                $this->collectionColumn => $collectionName,
                $this->keyColumn        => $key,
                $this->valueColumn      => serialize($data)
            ))
            ->execute()
        ;
    }

    public function delete($collectionName, $key)
    {
        $this->api->delete($this->table)
            ->condition($this->collectionColumn, $collectionName)
            ->condition($this->keyColumn, $key)
            ->execute()
        ;
    }

    public function update($collectionName, $key, array $data)
    {
        $this->api
            ->update($this->table)
            ->fields(array($this->valueColumn => $data))
            ->condition($this->collectionColumn, $collectionName)
            ->condition($this->keyColumn, $key)
            ->execute()
        ;
    }

    public function find($collectionName, $key)
    {
        $data = db_select($this->table)
            ->fields($this->table, array($this->valueColumn))
            ->condition($this->collectionColumn, $collectionName)
            ->condition($this->keyColumn, $key)
            ->execute()
            ->fetchColumn()
        ;

        if (!$data) {
            throw new NotFoundException();
        }

        return unserialize($data);
    }

}
