# MessageQueue #

A module extending functionalities from `magento/framework-message-queue` component.

### Features ###

* custom implementation of `Magento\Framework\MessageQueue\ConsumerInterface` making possible injection of envelope callback,
which allows to introduce custom message consumption easily without copy-paste of whole class
* a few implementations of `BKubicki\MessageQueue\Queue\Consumer\EnvelopeCallback\EnvelopeCallbackInterface`, each handling
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
composer require bkubicki/message-queue
```

##### Downloading ZIP

Download a ZIP version of the module and unpack it into your project into
```
app/code/BKubicki/MessageQueue
```
If you use ZIP file you will need to install all dependencies of the module
manually


#### Install the module

Run this command
```
bin/magento module:enable BKubicki_MessageQueue
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

## Explicit queue declaration

Core creates queues only as a side effect of `queue_topology.xml` bindings and takes the queue arguments from the
binding, so the very same arguments end up on the queue and on the binding. This module stops that: queues derived from
bindings are created **without arguments**, and arguments are defined on queues declared in `etc/queue_declaration.xml`:

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:module:BKubicki_MessageQueue:etc/queue_declaration.xsd">
    <queue name="create_entity" connection="amqp" durable="true" autoDelete="false">
        <arguments>
            <argument name="x-dead-letter-exchange" xsi:type="string">entity.dead_letter</argument>
        </arguments>
    </queue>
</config>
```

* files from all modules are merged by queue `name` + `connection`
* binding `<arguments>` are passed to the binding only, never to the queue
* a queue without declaration is still created from its binding, just without arguments
* a declared queue without any binding is created as well
