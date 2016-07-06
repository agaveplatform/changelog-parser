## [0.1.3] - 2016-07-07
### Added
- Added support for fetching Changelog files from URL in the `source=<url>` query parameter.
- Added support for forcing cache invalidation using the `force=true` query parameter.
- Added support for fetching the latest entry by specifying the `latest=true` query parameter.
- Added the [Agave Science API](http://agaveapi.co) [CHANGELOG.MD](https://bitbucket.org/agaveapi/agave/raw/develop/CHANGELOG.md) as the default content returned.
- Added Dockerfile and Docker Compose files for easy deployment.

### CHANGED
- Updated to run as a web API.
- Updated parser to work with URLs as well as files.

### REMOVED
- No change.


## Unreleased
### Added
- Cache manager unit tests

### Changed
- Cache files format

## [0.1.2] - 2016-03-01
### Added
- Packagist badges
- Scrutinizer badges
- Cache manager
- Configurable cache validity time
- Multiple changelog files caching

### Changed
- Release item keys format

## [0.1.1] - 2016-03-01
### Added
- Travis-CI configuration file
- Scrutinizer-CI configuration file

## [0.1.0] - 2016-03-01
### Added
- README file
- Driver abstract class
- Driver dedicated for JSON format
- Changelog parser
- Unit tests
- Example folder with test script
- README file
- Library manager
- Last version getter for JSON Driver
- All versions getter for JSON Driver
