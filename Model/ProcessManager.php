<?php
namespace Thomas\Kafka\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Filesystem\DriverPool;
use Magento\Framework\Filesystem\File\WriteFactory;

class ProcessManager
{
    const PID_FILE_EXT = '.pid';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var WriteFactory
     */
    private $writeFactory;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * ProcessManager constructor.
     * @param Filesystem $filesystem
     * @param WriteFactory $writeFactory
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Filesystem $filesystem,
        WriteFactory $writeFactory,
        DirectoryList $directoryList
    )
    {
        $this->filesystem = $filesystem;
        $this->writeFactory = $writeFactory;
        $this->directoryList = $directoryList;
    }


    /**
     * Check if the listener is running
     *
     * @param string $consumerName
     * @return bool
     */
    public function isRun($consumerName)
    {
        $pid = $this->getPid($consumerName);
        if ($pid) {
           return $this->checkIsProcessExists($pid);
        }

        return false;
    }

    /**
     * Checking if the process is actually running
     *
     * @param int $pid
     * @throws \RuntimeException
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @return bool
     */
    private function checkIsProcessExists($pid)
    {
        if (!function_exists('exec')) {
            throw new \RuntimeException('The exec function is not available.');
        }

        exec(escapeshellcmd('ps -p ' . $pid), $output, $code);

        $code = (int)$code;

        switch ($code) {
            case 0:
                return true;
                break;
            case 1:
                return false;
                break;
            default:
                throw new \RuntimeException('Exec returned a non-null response', $code);
                break;
        }
    }

    /**
     * Get the PID of the listener by name
     *
     * @param string $consumerName
     * @return bool|int
     */
    public function getPid($consumerName)
    {
        $pidFile = $consumerName . static::PID_FILE_EXT;
        /** @var WriteInterface $directory */
        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);

        if ($directory->isExist($pidFile)) {
            return (int)$directory->readFile($pidFile);
        }

        return false;
    }


    /**
     * Get the path to the file with PID
     *
     * @param string $consumerName
     * @return string
     */
    public function getPidFilePath($consumerName)
    {
        return $this->directoryList->getPath(DirectoryList::VAR_DIR) . '/' . $consumerName . static::PID_FILE_EXT;
    }

    /**
     * Save the process PID to a file
     *
     * @param string $pidFilePath
     */
    public function savePid($pidFilePath)
    {
        $file = $this->writeFactory->create($pidFilePath, DriverPool::FILE, 'w');
        $file->write(function_exists('posix_getpid') ? posix_getpid() : getmypid());
        $file->close();
    }
}
