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

use App\Model\User;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @Command
 */
class UserRegistCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('user:regist');
    }

    public function configure()
    {
        $this->setDescription('注册用户信息');
        $this->addArgument('token', InputArgument::REQUIRED, '用户TOKEN');
        $this->addOption('name', 'N', InputOption::VALUE_REQUIRED, '用户名');
    }

    public function handle()
    {
        $token = $this->input->getArgument('token');
        $name = $this->input->getOption('name');
        if (empty($name)) {
            $this->error('请输入用户名');
            return;
        }

        $user = new User();
        $user->token = $token;
        $user->name = $name;
        $user->save();

        $this->info(sprintf('用户创建完毕，请使用 %s 登录系统', $token));
    }
}
