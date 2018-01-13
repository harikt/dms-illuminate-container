# Illuminate DMS container

When working with [DMS](https://github.com/dms-org/core), I noticed the container need to be used in scafolding from cli and also for web.

The [web.laravel](https://github.com/dms-org/web.laravel/blob/644dfa5ad4b17d8ba50d1758621e2e60f6bf5240/src/Ioc/LaravelIocContainer.php) contains the implementation, so has copied from it with some additional methods and more tests.

1. alias
1. makeWith
1. getIlluminateContainer

## Installation

```
composer require harikt/dms-illuminate-container
```

## License

MIT