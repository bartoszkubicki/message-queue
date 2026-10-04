### Unreleased ###
* composer package renamed from `lizardmedia/module-message-queue` to `bartoszkubicki/message-queue` (PHP namespace
`LizardMedia\MessageQueue` and module name `LizardMedia_MessageQueue` are unchanged)
* widened `php` constraint to `~8.1.0||~8.2.0||~8.3.0` for PHP 8.1-8.3 / Magento Open Source 2.4.7 compatibility
* added explicit `php-amqplib/php-amqplib` (`~3.2.0`) requirement, matching the version shipped with
`magento/framework-amqp` 100.4.x on Magento 2.4.7; verified `PhpAmqpLib\Wire\AMQPTable::set()`/`getNativeData()`
usage in `Envelope\RetryLimitOverflowResolver` is unchanged in that version
* verified against Magento 2.4.7 core sources that `ConsumerInterface`, `EnvelopeInterface`, `QueueInterface`,
`CallbackInvokerInterface`, `ConsumerConfigurationInterface` and related MessageQueue framework classes this module
depends on are unchanged since the 100.3/100.4 era, so no signature fixes were required in this module's code
* no PHP 8 runtime-incompatible code (dynamic properties, curly-brace offsets, removed functions, etc.) was found in
`Api/`, `Console/`, `Envelope/`, `Queue/` or `Test/`; codebase already used `declare(strict_types=1)` and typed
properties throughout

### 1.0.0 ###
* custom implementation of `Magento\Framework\MessageQueue\ConsumerInterface` making possible injection of envelope callback,
which allows to introduce custom message consumption easily without copy-paste of whole class
* a few implementations of `LizardMedia\MessageQueue\Queue\Consumer\EnvelopeCallback\EnvelopeCallbackInterface`, each handling
message in its specific way, including `x-death` parameters support
