# Illuminate DMS container

[![Build Status](https://travis-ci.org/harikt/dms-illuminate-container.svg?branch=master)](https://travis-ci.org/harikt/dms-illuminate-container) [![quality-score](https://scrutinizer-ci.com/g/harikt/dms-illuminate-container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/harikt/dms-illuminate-container)

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