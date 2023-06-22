# Magento 2 Module Codepeak SearchTermsCleanup

## Installation

```
composer require codepeak/magento2-module-searchtermscleanup
php bin/magento module:enable Codepeak_SearchTermsCleanup
php bin/magento setup:upgrade
php bin/magento cache:flush
```

## Usage

This module will handle itself. Every hour it will clean up the search terms table, marking search terms that looks inappropriated as "suggested" and deleting search terms that are marked as "inactive".