<?php
namespace Thomas\Kafka\Consumers;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Thomas\Kafka\Data\ConsumerMessageInterface;

class Example implements ObserverInterface
{
    /**
     * @inheritdoc
     */
    public function execute(Observer $observer)
    {
        /**
         * @var \Thomas\Kafka\Model\Consumers $consumers
         */
        $consumers = $observer->getEvent()->getConsumers();

        $numberOfConsumers = 1;

        for ($i = 1; $i <= $numberOfConsumers; $i++) {
            $consumers->add(
                'thomasKafkaExample' . '_' . $i,
                \Thomas\Kafka\Consumers\Example::class,
                'receive',
                'thomas.kafka.example',
                'exampleConsumerGroup',
                $this->getAvroSchema()
            );
        }
    }

    /**
     * @param ConsumerMessageInterface $msg
     * @return bool
     */
    public function receive($msg)
    {
        print_r($msg->getBody());
        return true;
    }

    protected function getAvroSchema()
    {
        return <<<JSON
            {
            "name":"member",
            "type":"record",
            "fields":[
                    {
                    "name":"member_id",
                    "type":"int"
                    },
                    {
                    "name":"member_name",
                    "type":"string"
                    }
                ]
            }
JSON;
    }
}
