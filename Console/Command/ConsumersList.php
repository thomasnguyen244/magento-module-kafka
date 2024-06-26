<?php
namespace Thomas\Kafka\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumersList extends Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * @var \Thomas\Kafka\Model\Consumers
     */
    private $consumers;

    /**
     * ConsumersList constructor.
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Thomas\Kafka\Model\Consumers $consumers
    ) {
        $this->appState = $appState;
        $this->consumers = $consumers;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    )
    {
        foreach ($this->consumers->getList() as $name => $options) {
            $output->writeln('['.$options['topic'].'] '.$name . " | " . $options['class'] . ':' . $options['action'] . "()");
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("thomas_kafka:consumers:list");
        $this->setDescription("List of listeners");
        parent::configure();
    }
}
