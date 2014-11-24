<?php

namespace Drupal\at;

use Drupal\at\Drupal\DrupalCacheAPI;
use InvalidArgumentException;

class Cache
{

    /** @var string */
    private $bin = 'cache';

    /** @var string */
    private $id;

    /** @var string Time to live. */
    private $ttl;

    /** @var bool Rebuild data if cached data is empty/false/null. */
    private $allowEmpty;

    /** @var boolean Flag to rebuild cache data by pass. */
    private $reset;

    /** @var string[] Attached tags. */
    private $tags;

    /** @var callable */
    private $callback;

    /** @var mixed[] Callback arguments. */
    private $arguments;

    /** @var DrupalCacheAPI */
    private $api;

    public function __construct($options, $callback, $arguments = array(), $api = NULL)
    {
        $this->setOptions($options);

        $this->callback = $callback;
        $this->arguments = $arguments;

        // No cache_id, can not fetch, can not write, this function is useless.
        if (empty($this->id) || !is_string($this->id)) {
            throw new InvalidArgumentException('Please provide a valid cache ID');
        }

        $this->api = $api;
    }

    public function setOptions($options)
    {
        $defaults = array(
            'bin'   => 'cache',
            'id'    => '',
            'ttl'   => '+ 15 minutes',
            'reset' => FALSE,
            'tags'  => array());

        foreach ($defaults as $k => $v) {
            $this->{$k} = isset($options[$k]) ? $options[$k] : $v;
        }
    }

    /**
     * Fetch the cached data
     *
     * @return  mixed
     */
    public function get()
    {
        if (!$this->reset && $cache = $this->api->get($this->id, $this->bin)) {
            if (!empty($cache->data) || $this->allowEmpty) {
                return $cache->data;
            }
        }
        return $this->fetch();
    }

    /**
     * Fetch data.
     *
     * @return mixed
     */
    public function fetch()
    {
        if (!is_callable($this->callback)) {
            throw new InvalidArgumentException('Invalid callback: ' . print_r($this->callback, TRUE));
        }
        $this->write($return = call_user_func_array($this->callback, $this->arguments));
        return $return;
    }

    /**
     * Write data to cache bin.
     *
     * @param  mixed $data
     */
    protected function write($data)
    {
        if (FALSE !== $this->api->set($this->id, $data, $this->bin, strtotime($this->ttl))) {
            if (!empty($this->tags)) {
                $this->removeAllTags();
                foreach ($this->tags as $tag) {
                    $this->addTag($tag);
                }
            }
        }
    }

    /**
     * Add tag to a cache item.
     *
     * @param string $tag
     * @see   at_base_flush_caches()
     */
    public function addTag($tag)
    {
        return $this->api
                ->insert('at_base_cache_tag')
                ->fields(['bin' => $this->bin, 'cid' => $this->id, 'tag' => $tag])
                ->execute()
        ;
    }

    public function removeAllTags()
    {
        return $this->api
                ->delete('at_base_cache_tag')
                ->condition('bin', $this->bin)
                ->condition('cid', $this->id)
                ->execute()
        ;
    }

    /**
     * Remove a tag from a cache item.
     *
     * @param  string $tag
     */
    public function removeTag($tag)
    {
        return $this->api
                ->delete('at_base_cache_tag')
                ->condition('bin', $this->bin)
                ->condition('cid', $this->id)
                ->condition('tag', $tag)
                ->execute()
        ;
    }

}
