# Magento 2 Module Codepeak SearchTermsCleanup

## Installation

```
composer require codepeak/magento2-module-searchtermscleanup
php bin/magento module:enable Codepeak_SearchTermsCleanup
php bin/magento setup:upgrade
php bin/magento cache:flush
```

## Usage

This module will check the "search_query" table every 6th hour and see if it can find any searches that might be inappropriate. If it finds any, it will mark them as "inactive" and they will not be shown in the similar searches.