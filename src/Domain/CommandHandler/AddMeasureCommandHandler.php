<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 01/07/19
 */

namespace App\Domain\CommandHandler;

use App\Domain\Command\AddMeasureCommand;
use App\Domain\Command\CommandInterface;
use App\Domain\Entity\MeasureEntity;
use App\Domain\Repository\MeasureRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class AddMeasureCommandHandler
 * @package App\Domain\CommandHandler
 */
class AddMeasureCommandHandler implements CommandHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var MeasureRepositoryInterface */
    protected $measureRepository;

    /**
     * AddMeasureCommandHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->measureRepository = $container->get(MeasureRepositoryInterface::class);
        $this->logger = $container->get('logger');
    }

    /**
     * @return array
     */
    public static function getSupportedCommands(): array
    {
        return [
            AddMeasureCommand::class
        ];
    }

    /**
     * @param CommandInterface|AddMeasureCommand $command
     * @return CommandResult
     */
    public function handle(CommandInterface $command): CommandResult
    {
        $measure = (new MeasureEntity())
            ->setDataSource($command->getDataSource())
            ->setLocation($command->getLocation())
            ->setCategory($command->getCategory())
            ->setMetric($command->getMetric())
            ->setUnit($command->getUnit())
            ->setValue($command->getValue())
            ->setMeasuredDate($command->getMeasuredDate());

        $this->logger->debug(sprintf('Saving measure: %s', $measure->getName()));

        $this->measureRepository->save($measure);

        return new CommandResult(true);
    }
}
