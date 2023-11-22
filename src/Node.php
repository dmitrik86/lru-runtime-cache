<?php
namespace LRURuntimeCache;

class Node
{
    /**
     * Previous node
     *
     * @var null
     */
    public $prev = null;

    /**
     * Next node
     *
     * @var null
     */
    public $next = null;

    /**
     * Value of node
     *
     * @var null
     */
    public $value = null;

    /**
     * The key of hash table item.
     * 
     * @var null 
     */
    public $key = null;

    public function __construct($key = null, $value = null, $prev = null, $next = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->prev = $prev;
        $this->next = $next;
    }
}