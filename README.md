# magento-module-kafka
Apache Kafka Magento2 module, Magento 2 Integrate With Kafka

## Setup and config

Magento setup php packages via composer:

```
composer require flix-tech/avro-serde-php
composer require jobcloud/php-kafka-lib
composer require ext-rdkafka
```

Local Kafka + Control Center via Docker: https://docs.confluent.io/current/quickstart/ce-docker-quickstart.html

Default admin
--
http://localhost:9021/ - Control Center
localhost:9092 - Kafka

Forked from:[Module Kafka](https://github.com/neverovsky/magento-module-kafka) for maintain and updating module.
