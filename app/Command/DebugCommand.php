<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Command;

use App\Amqp\Producer\SendMessageProducer;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @Command
 */
class DebugCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('debug:send-message');
    }

    public function configure()
    {
        $this->setDescription('Send Message to websocket client.');
        $this->addArgument('fd', InputArgument::REQUIRED, '目标FD');
    }

    public function handle()
    {
        $fd = $this->input->getArgument('fd');

        $data = [
            'protocal' => 'text',
            'data' => 'Hello World',
        ];

        amqp_produce(new SendMessageProducer((int) $fd, $data));
    }
}
