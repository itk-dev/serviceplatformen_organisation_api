# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.1] 2025-08-19

* Ensured `SF1500_ORGANISATION_TEST_MODE` environment variable is boolean.

## [1.1.0] 2025-04-08

* Upgraded to PHP 8.3
* Upgraded to Symfony 6.4

## [1.0.3] 2024-02-29

* Restore to default monolog config.

## [1.0.2] 2024-02-20

* Updated data fetchers to retry 5 times due to sporadic soap faults.
* Added logging via `symfony/monolog-bundle`
* Upped offset for when certificate locator token should be refreshed.

## [1.0.1] 2024-01-16

* Refreshed Azure Key Vault access token if it expires during fetch data run.

## [1.0.0] 2024-01-11

* Initial release

[Unreleased]: https://github.com/itk-dev/serviceplatformen_organisation_api/compare/1.1.0...HEAD
[1.1.0]: https://github.com/itk-dev/serviceplatformen_organisation_api/compare/1.0.3...1.1.0
[1.0.3]: https://github.com/itk-dev/serviceplatformen_organisation_api/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/itk-dev/serviceplatformen_organisation_api/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/itk-dev/serviceplatformen_organisation_api/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/itk-dev/serviceplatformen_organisation_api/releases/tag/1.0.0
