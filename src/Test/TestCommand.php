<?php

declare(strict_types=1);

namespace AdgoalCommon\AlertingBundle\Test;

use AdgoalCommon\Base\Application\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TestCommand implements CommandInterface
{

    public function getUuid(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
