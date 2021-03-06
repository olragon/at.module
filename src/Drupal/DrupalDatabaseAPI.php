<?php

namespace Drupal\at\Drupal;

use DatabaseStatementInterface;
use DeleteQuery;
use InsertQuery;
use MergeQuery;
use SelectQuery;
use UpdateQuery;

class DrupalDatabaseAPI
{

    /**
     * @param string $table
     * @param string $alias
     * @param array $options
     * @return SelectQuery
     */
    public function select($table, $alias = NULL, array $options = array())
    {
        return db_select($table, $alias, $options);
    }

    /**
     * @param string $table
     * @param array $options
     * @return UpdateQuery
     */
    public function update($table, array $options = array())
    {
        return db_update($table, $options);
    }

    /**
     * @param string $table
     * @param array $options
     * @return DeleteQuery
     */
    public function delete($table, array $options = array())
    {
        return db_delete($table, $options);
    }

    /**
     * @param string $table
     * @param array $options
     * @return InsertQuery
     */
    public function insert($table, array $options = array())
    {
        return db_insert($table, $options);
    }

    /**
     * @param string $table
     * @param array $options
     * @return MergeQuery
     */
    public function merge($table, array $options = array())
    {
        return db_merge($table, $options);
    }

    /**
     * @param string $query
     * @param array $args
     * @param array $options
     * @return DatabaseStatementInterface
     */
    public function query($query, array $args = array(), array $options = array())
    {
        return db_query($query, $args, $options);
    }

}
