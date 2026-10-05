# MessageQueue #

A module extending functionalities from `magento/framework-message-queue` component.

### Features ###

* custom implementation of `Magento\Framework\MessageQueue\ConsumerInterface` making possible injection of envelope callback,
which allows to introduce custom message consumption easily without copy-paste of whole class
* a few implementations of `BartoszKubicki\MessageQueue\Queue\Consumer\EnvelopeCallback\EnvelopeCallbackInterface`, each handling
message in its specific way, including `x-death` parameters support 

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

* Magento 2.4.7+ (tested against 2.4.8)
* PHP 8.1/8.2/8.3
* RabbitMQ 3.8+ (tested against 4.1)
* No patches required. [bartoszkubicki/magento2-mq-patches](https://github.com/bartoszkubicki/magento2-mq-patches) previously listed here is now archived — the Magento Message Queue bugs it addressed were fixed upstream in Magento core (see that repo's README for details on which core version fixed each one).

### Installing

#### Download the module

##### Using composer (suggested)

Simply run

```
composer require bartoszkubicki/message-queue
```

##### Downloading ZIP

Download a ZIP version of the module and unpack it into your project into
```
app/code/BartoszKubicki/MessageQueue
```
If you use ZIP file you will need to install all dependencies of the module
manually


#### Install the module

Run this command
```
bin/magento module:enable BartoszKubicki_MessageQueue
bin/magento setup:upgrade
```

## Usage

To make poison pill stop your consumers you have to run them with param `--max-messages`. Trigger a poison pill with Magento core's own `bin/magento queue:consumers:restart` — this module used to ship its own `lm:queue:consumers:poison` command for this, but it was functionally identical to Magento's command (both just call `PoisonPillPutInterface::put()`), which has been part of core since Magento 2.4.2/2.4.3, so the duplicate was removed.

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/bartoszkubicki/message-queue/tags). 

## Authors

* **Bartosz Kubicki** - *Initial work, fixes & maintenance* - [bartoszkubicki](https://github.com/bartoszkubicki)

See also the list of [contributors](https://github.com/bartoszkubicki/message-queue/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
