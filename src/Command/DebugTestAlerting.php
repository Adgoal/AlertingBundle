<?php

declare(strict_types=1);

namespace AdgoalCommon\AlertingBundle\Command;

use AdgoalCommon\Alerting\Infrastructure\Event\Publisher\CommandAlertPublisher;
use AdgoalCommon\CommandBus\Event\CommandFailed;
use AdgoalCommon\AlertingBundle\Test\TestCommand;
use ErrorException;
use Exception;
use stdClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProgramRunCommand.
 */
class DebugTestAlerting extends Command
{
    private $commandAlertPublisher;


    /**
     * AfmCommand constructor.
     */
    public function __construct(
        CommandAlertPublisher $commandAlertPublisher
    ){
        parent::__construct('debug:test-alerting');
        $this->commandAlertPublisher = $commandAlertPublisher;
    }

    protected function configure(): void
    {
        $this->setName('debug:test-alerting');
    }

    /**
     * @throws Exception
     *
     * @phan-suppress PhanUnusedProtectedMethodParameter
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            throw new ErrorException('Test Exception');
        } catch (ErrorException $exception) {
            $this->commandAlertPublisher->handle(new CommandFailed(new TestCommand(), $exception));
        }

        return 0;
    }
}
