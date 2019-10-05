<?php declare(strict_types=1);

namespace App\Monolog;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class DoctrineHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function write(array $record)
    {
        $log = new Log(
            $record['message'],
            $record['level'],
            $record['level_name'],
            $record['context'],
            $record['extra']
        );

        $this->em->persist($log);
        $this->em->flush();
    }
}
