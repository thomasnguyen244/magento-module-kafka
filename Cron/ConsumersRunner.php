<?php
namespace Thomas\Kafka\Cron;

use Magento\Framework\ShellInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Thomas\Kafka\Helper\Data as Helper;
use Thomas\Kafka\Model\Consumers;
use Thomas\Kafka\Model\ProcessManager;

/**
 * Launch listeners on cron (if not launched)
 */
class ConsumersRunner
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var Consumers
     */
    private $consumers;

    /**
     * @var ShellInterface
     */
    private $shellBackground;

    /**
     * @var PhpExecutableFinder
     */
    private $phpExecutableFinder;

    /**
     * @var ProcessManager
     */
    private $processManager;

    /**
     * construct
     *
     * @param PhpExecutableFinder $phpExecutableFinder
     * @param ShellInterface $shellBackground
     * @param ProcessManager $processManager
     * @param Helper $helper
     * @param Consumers $consumers
     */
    public function __construct(
        PhpExecutableFinder $phpExecutableFinder,
        ShellInterface $shellBackground,
        ProcessManager $processManager,
        Helper $helper,
        Consumers $consumers
    ) {
        $this->consumers = $consumers;
        $this->helper = $helper;
        $this->phpExecutableFinder = $phpExecutableFinder;
        $this->shellBackground = $shellBackground;
        $this->processManager = $processManager;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if (!$this->helper->isConsumersCronEnabled()) {
            return;
        }
        $php = $this->phpExecutableFinder->find() ?: 'php';
        foreach ($this->consumers->getList() as $name => $options) {
            if ($this->processManager->isRun($name)) {
                continue;
            }

            $arguments = [
                $name
            ];

            $command = $php . ' ' . BP . '/bin/magento thomas_kafka:consumers:start %s';

            $this->shellBackground->execute($command, $arguments);
        }
    }
}
