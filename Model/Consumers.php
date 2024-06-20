<?php

namespace Thomas\Kafka\Model;

use Magento\Framework\Event\ManagerInterface as EventManager;

class Consumers
{
    protected $list = [];

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Subscribe the listener
     *
     * @param $handlerName
     * @param $handlerClassName
     * @param $handlerAction
     * @param $topicName
     * @param $consumerGroup
     * @param $avroSchema
     */
    public function add($handlerName, $handlerClassName, $handlerAction, $topicName, $consumerGroup, $avroSchema)
    {
        $this->list[$handlerName] = [
            'class' => $handlerClassName,
            'action' => $handlerAction,
            'topic' => $topicName,
            'consumer_group' => $consumerGroup,
            'avro_schema' => $avroSchema
        ];
    }

    /**
     * We get a list of listeners
     *
     * @return array
     */
    public function getList()
    {
        if (!$this->list) {
            $this->eventManager->dispatch(
                'thomas_kafka_list_consumers_before',
                [
                    'consumers' => $this
                ]
            );
        }
        return $this->list;
    }
}
