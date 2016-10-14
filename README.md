Changelog Parser
===============

[![Latest Stable Version](https://poser.pugx.org/kern046/changelog-parser/v/stable)](https://packagist.org/packages/kern046/changelog-parser)
[![Latest Unstable Version](https://poser.pugx.org/kern046/changelog-parser/v/unstable)](https://packagist.org/packages/kern046/changelog-parser)
[![Build Status](https://scrutinizer-ci.com/g/Kern046/changelog-parser/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Kern046/changelog-parser/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Kern046/changelog-parser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Kern046/changelog-parser/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Kern046/changelog-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Kern046/changelog-parser/?branch=master)
[![Total Downloads](https://poser.pugx.org/kern046/changelog-parser/downloads)](https://packagist.org/packages/kern046/changelog-parser)
[![License](https://poser.pugx.org/kern046/changelog-parser/license)](https://packagist.org/packages/kern046/changelog-parser)

Introduction
------------

This library is meant to parse changelog files and convert its data to different formats.

It would be used to get dynamically data from a changelog file to inform users about the different versions and their changes.

With this library it is easy to use changelog data in any way.

Installation
------------

You can use composer to set the library as your project dependency

```shell
composer require kern046/changelog-parser
```

Usage
------------

To use this library, you can create an instance of the changelog manager

```php
use ChangelogParser\Manager\ChangelogManager;

$changelogManager = new ChangelogManager();
```

To get the last version data of your changelog file, write the following code :

```php
// The second parameter is optional, default is 'json'
$changelogManager->getLastVersion('CHANGELOG.md', 'json');
```

To get a changelog from a remote URL, write the following code:

```php
// The second parameter is optional, default is 'json'
$changelogManager->getLastVersion('http://example.com/CHANGELOG.md', 'json');
```

To get all data contained in the changelog file, use the following method :

```php
// The second parameter is optional, default is 'json'
$changelogManager->getAllVersions('CHANGELOG.md', 'json');
```

The results of these functions are cached.

The default cache validity time is one hour.

You can modify it using the following way :

```php
$cacheManager = $changelogManager->getCacheManager();
// The first argument is the validity time in seconds
// In the current example, the cache validity time is one day
$cacheManager->setCacheTime(60 * 60 * 24);
```

API
------------

This parser may be run as an API. Simply copy the repository to your web server and reference the `example` directory. For convenience, a Docker Compose file is included. Running the following command will start a Docker container with the changelog-parser API running at [http://<docker_host>:8888

```
docker-compose up -d
```

The API accepts three query parameters:

| Name | Type | Description |
|------|------|-------------|
| source | url | A valid URL to the changelog you wish to parse. |
| force  | boolean | True if the cache should be flushed and the changelog fetched and regenerated. |
| latest | boolean | True if only the latest entry in the changelog should be returned. |
