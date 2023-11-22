<?php
namespace LRURuntimeCache\Tests;

use LRURuntimeCache\LRURuntimeCache;
use PHPUnit\Framework\TestCase;

class LRURuntimeCacheTest extends TestCase
{
    public function testGeneral()
    {
        $cache = new LRURuntimeCache(16);
        $keys = [1, 1, 1, 'foo', 'foo', 'bar', 'foo', 'foo', 'foo', 'foo', 'foo', 'foo'];
        $values = [null, 1, null, 'bar', null, null, null, 'bazz', null, null, null, null];
        $actions = ['get', 'set', 'get', 'set', 'has', 'has', 'get', 'set', 'has', 'get', 'delete', 'has'];
        $results = [null, null, 1, null, true, false, 'bar', null, true, 'bazz', null, false];
        $this->runTests($cache, $actions, $keys, $values, $results);
    }

    public function testOverflow()
    {
        $cache = new LRURuntimeCache(4);
        $keys = [1, 1, 1, 2, 3, 4, 1, 1, 2, 5, 2, 2, 3, 3];
        $values = [1, null, null, 2, 3, 4, null, null, null, 5, null, null, null, null];
        $actions = ['set', 'has', 'get', 'set', 'set', 'set', 'has', 'get', 'get', 'set', 'has', 'get', 'has', 'get'];
        $results = [null, true, 1, null, null, null, false, null, 2, null, true, 2, false, null];
        $this->runTests($cache, $actions, $keys, $values, $results);
    }

    /**
     * @param LRURuntimeCache $runtimeCache
     * @param array           $actions
     * @param array           $keys
     * @param array           $values
     * @param int             $actionNumber
     *
     * @return bool|null
     * @throws \Exception
     */
    protected function call(
        LRURuntimeCache $runtimeCache,
        array           $actions,
        array           $keys,
        array           $values,
        int             $actionNumber
    ) {
        switch ($actions[$actionNumber]) {
            case 'get':
                return $runtimeCache->get($keys[$actionNumber]);
            case 'set':
                return $runtimeCache->set($keys[$actionNumber], $values[$actionNumber]);
            case 'delete':
                return $runtimeCache->delete($keys[$actionNumber]);
            case 'has':
                return $runtimeCache->has($keys[$actionNumber]);
            default:
                throw new \Exception('Invalid test case method.');
        }
    }

    /**
     * @param LRURuntimeCache $runtimeCache
     * @param array           $actions
     * @param array           $keys
     * @param array           $values
     * @param array           $results
     *
     * @return void
     * @throws \Exception
     */
    protected function runTests(
        LRURuntimeCache $runtimeCache,
        array           $actions,
        array           $keys,
        array           $values,
        array           $results
    ) {
        if (
            ($numberOfItems = count($actions)) !== count($values)
            || count($keys) !== $numberOfItems
            || count($results) !== $numberOfItems
        ) {
            throw new \Exception('Invalid test case');
        }
        for ($i = 0; $i < $numberOfItems; ++$i) {
            $this->assertEquals(
                $results[$i],
                $this->call(
                    $runtimeCache,
                    $actions,
                    $keys,
                    $values,
                    $i
                )
            );
        }
    }
}