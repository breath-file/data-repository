<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Domain\CommandHandler;

/**
 * Class CommandResult
 * @package App\Domain\CommandHandler
 */
class CommandResult
{
    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var mixed|null
     */
    protected $result;

    /**
     * CommandResult constructor.
     * @param bool  $success
     * @param mixed $result
     */
    public function __construct(bool $success, $result = null)
    {
        $this->success = $success;
        $this->result = $result;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return mixed|null
     */
    public function getResult()
    {
        return $this->result;
    }
}
