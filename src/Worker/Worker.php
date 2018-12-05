<?php

namespace IntecPhp\Worker;

use Pheanstalk\Pheanstalk;
use Psr\Log\LoggerInterface;

abstract class Worker
{
    protected $tube;

    abstract public function execute(array $data);

    final public function watch()
    {
        if (!($this->tube instanceof Pheanstalk)) {
            throw new \RuntimeException('O tubo deve ser um instância de \'Pheanstalk\'');
        }

        $this->tube->ignore('default');
        $job = $this->tube->reserve();
        $data = json_decode($job->getData(), true);
        $this->execute($data);
        $this->tube->delete($job);
    }
}
