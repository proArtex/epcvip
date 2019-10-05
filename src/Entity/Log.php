<?php declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 */
class Log
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $context;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $level;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $levelName;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $extra;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    public function __construct(string $message, int $level, string $levelName, array $context, array $extra)
    {
        $this->message = $message;
        $this->level = $level;
        $this->levelName = $levelName;
        $this->context = $context;
        $this->extra = $extra;
    }
}
