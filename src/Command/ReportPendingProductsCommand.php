<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\ProductRepository;
use App\Service\EmailNotificationService;
use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportPendingProductsCommand extends Command
{
    protected static $defaultName = 'app:report-pending-products';

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var EmailNotificationService
     */
    private $notificationService;

    public function __construct(ProductRepository $productRepository, EmailNotificationService $notificationService)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->notificationService = $notificationService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Looking for products on “pending” for a week or more and send some sort of notification');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $count = $this->productRepository->countPendingOlderThan(new DateTimeImmutable('now - 1 week'));
        $message = "Pending products found: {$count}";
        $io->success($message);

        if ($count) {
            $this->notificationService->notify($message);
        }
    }
}
