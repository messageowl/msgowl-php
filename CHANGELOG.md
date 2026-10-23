# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

## [1.1.1]

### Changed
- Re-release of 1.1.0 rebased onto the latest `main` (includes the PHP 8.4/8.5 CI matrix and test coverage fixes)

## [1.1.0]

### Added
- `appHash` parameter on `otp()->send()` and `otp()->resend()` for the Android SMS Retriever API
- Access key scopes documented in the README

## [1.0.0]

### Added
- Initial release of the MessageOwl PHP SDK
- Support for Messages, OTP, Groups, Contacts, Balance, and Sender IDs
- Laravel service provider and facade
- Full exception hierarchy with typed properties
