# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]
-

## [2.0.4] - 2019-12-22
- Improved error handling with exceptions.
- Updated usage of deprecated `addItem()` method.
- Updated payment status class name.

## [2.0.3] - 2019-08-26
- Updated packages.

## [2.0.2] - 2019-01-17
- Fixed "Fatal error: Uncaught Error: Call to undefined method jigoshop::get_option()".

## [2.0.1] - 2018-12-12
- Update item methods in payment data.

## [2.0.0] - 2018-05-14
- Switched to PHP namespaces.

## [1.0.6] - 2017-09-14
- Implemented `get_first_name()` and `get_last_name()`.

## [1.0.5] - 2017-01-25
- Added filter for payment source description and URL.
- Simplified gateway by always redirecting to the pay URL.

## [1.0.4] - 2016-07-06
- Use iDEAL payment method when payment method is required, but not set.

## [1.0.3] - 2016-04-12
- No longer use camelCase for payment data.

## [1.0.2] - 2016-03-23
- Removed status code from redirect in update_status.

## [1.0.1] - 2015-03-03
- Changed WordPress pay core library requirment from `~1.0.0` to `>=1.0.0`.

## 1.0.0 - 2015-01-20
- First release.

[unreleased]: https://github.com/wp-pay-extensions/jigoshop/compare/2.0.4...HEAD
[2.0.4]: https://github.com/wp-pay-extensions/jigoshop/compare/2.0.3...2.0.4
[2.0.3]: https://github.com/wp-pay-extensions/jigoshop/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/wp-pay-extensions/jigoshop/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/wp-pay-extensions/jigoshop/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/wp-pay-extensions/jigoshop/compare/1.0.6...2.0.0
[1.0.6]: https://github.com/wp-pay-extensions/jigoshop/compare/1.0.5...1.0.6
[1.0.5]: https://github.com/wp-pay-extensions/jigoshop/compare/1.0.4...1.0.5
[1.0.4]: https://github.com/wp-pay-extensions/jigoshop/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/wp-pay-extensions/jigoshop/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/wp-pay-extensions/jigoshop/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/wp-pay-extensions/jigoshop/compare/1.0.0...1.0.1
