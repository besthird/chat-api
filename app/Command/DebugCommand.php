<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Command;

use App\Chat\Constants;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Nsq\Nsq;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;
use Swoole\Timer;
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
        $this->addArgument('token', InputArgument::REQUIRED, '目标 TOKEN');
    }

    public function handle()
    {
        $token = $this->input->getArgument('token');

        $data = [
            'protocal' => 'text',
            'data' => 'Hello World',
        ];

        di()->get(Nsq::class)->publish(Constants::SEND_MESSAGE, Json::encode([
            'token' => $token,
            'data' => $data,
        ]));

        Timer::clearAll();
    }
}
