<?php

declare(strict_types = 1);

namespace App\Lib\Misc;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class DockerMonologHandler
 */
class DockerMonologHandler extends AbstractProcessingHandler
{

    /**
     * Log resource
     * @var
     */
    private $resource;

    /**
     * Log command
     * @var string
     */
    private $command;

    /**
     * Construct
     * @param  int                      $processId
     * @param  int                      $fileDescriptor
     * @param                           $level
     * @param  bool                     $bubble
     * @param  FormatterInterface|null  $formatter
     */
    public function __construct(int $processId = 1, int $fileDescriptor = 2, $level = Logger::DEBUG, bool $bubble = true, FormatterInterface $formatter = null)
    {
        $this->command = sprintf('cat - >> /proc/%d/fd/%d', $processId, $fileDescriptor);

        parent::__construct($level, $bubble);

        if (null !== $formatter) {
            $this->setFormatter($formatter);
        }
    }

    /**
     * Close resource
     * @return void
     */
    public function close(): void
    {
        if (is_resource($this->resource)) {
            pclose($this->resource);
        }

        parent::close();
    }

    /**
     * Write to resource
     * @param  array  $record
     * @return void
     */
    protected function write(array $record): void
    {
        if (!is_resource($this->resource)) {
            $this->resource = popen($this->command, 'w');
        }

        fwrite($this->resource, (string)$record['formatted']);
        pclose($this->resource);
    }

    /**
     * Get default log formatter
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter("[%datetime%][%level_name%][%channel%]: %context%%message%\n", "Y-m-d H:i:s.u", false, true);
    }

}
