# LRU Runtime Cache

The package provides the least recently used runtime cache class.

# How to install

Add the package

```
composer require lru-runtime-cache/lru-runtime-cache
```

# How to use

Create a class `LRURuntimeCache`

```
use LRURuntimeCache\LRURuntimeCache;

$runtimeCache = new LRURuntimeCache();
```

Constructor has one optional param $maxSize. It is used to set the number of maximum runtime cache items.


# Methods

1. Add item to runtime cache

```
$runtimeCache->set($key, $value);
```

2. Get value from cache

```
$runtimeCache->get($key);
```

3. Check if key exists in runtime cache

```
$runtimeCache->has($key);
```

4. Delete item in cache

```
$runtimeCache->delete($key);
```

# Unit tests

1. Install unit test package

```
composer install --dev
```

2. Run unit tests

```
./vendor/bin/phpunit
```