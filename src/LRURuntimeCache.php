<?php
namespace LRURuntimeCache;

class LRURuntimeCache
{
    /**
     * Number of maximum cache items.
     * If maximum size is null then cache does not overflow.
     *
     * @var int|null
     */
    protected ?int $maxSize = 512;

    /**
     * Number of items in runtime cache.
     *
     * @var int|null
     */
    protected int $size = 0;

    /**
     * Hash table of nodes.
     * 
     * @var Node[]
     */
    protected array $data = [];

    /**
     * Head of linked list.
     * 
     * @var Node|null
     */
    protected ?Node $head = null;

    /**
     * Tail of linked list.
     * 
     * @var Node|null
     */
    protected ?Node $tail = null;

    public function __construct(?int $maxSize = 512)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Get value from runtime cache if it exists.
     * The requested value moves to the head of list. Unused value will be deleted from cache at first.
     * 
     * Time complexity O(1).
     * 
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->data)) {
            return null;
        }
        $node = $this->data[$key];
        if ($this->head !== $node) {
            $this->removeNode($node);
            $this->addNode($node);
        }
        return $node->value;
    }

    /**
     * Set value to runtime cache.
     * If cache overflows then unused value will be deleted.
     * 
     * Time complexity O(1).
     * 
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        if (!array_key_exists($key, $this->data)) {
            ++$this->size;
        }
        if ($this->maxSize && $this->size && $this->size === $this->maxSize) {
            unset($this->data[$this->tail->key]);
            $this->removeNode($this->tail);
            --$this->size;
        }
        $node = new Node($key, $value);
        $this->data[$key] = $node;
        $this->addNode($node);
    }

    /**
     * Delete item from runtime cache.
     * 
     * Time complexity O(1).
     * 
     * @param $key
     *
     * @return void
     */
    public function delete($key)
    {
        if ($this->data[$key]) {
            $this->removeNode($this->data[$key]);
            unset($this->data[$key]);
            --$this->size;
        }
    }

    /**
     * Check if key exists in runtime cache.
     * The checked key moves to the head of list. Unused value will be deleted from cache at first.
     * 
     * Time complexity O(1).
     * 
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        if (!array_key_exists($key, $this->data)) {
            return false;
        }
        $node = $this->data[$key];
        $this->removeNode($node);
        $this->addNode($node);
        return true;
    }

    /**
     * Add node to linked list.
     * 
     * Time complexity O(1).
     * 
     * @param Node $node
     *
     * @return void
     */
    protected function addNode(Node $node)
    {
        if (!$this->head && !$this->tail) {
            $this->head = $node;
            $this->tail = $node;
        } else {
            $node->next = $this->head;
            $node->next->prev = $node;
            $this->head = $node;
        }
    }

    /**
     * Remove node from linked list.
     * 
     * Time complexity O(1).
     * 
     * @param Node $node
     *
     * @return void
     */
    protected function removeNode(Node $node)
    {
        if ($this->head === $node && $this->tail === $node) {
            $this->head = null;
            $this->tail = null;
        } elseif ($this->head === $node) {
            $node->next->prev = null;
            $this->head = $node->next;
        } elseif ($this->tail === $node) {
            $node->prev->next = null;
            $this->tail = $node->prev;
        } else {
            $node->prev->next = $node->next;
            $node->next->prev = $node->prev;
        }
    }
}