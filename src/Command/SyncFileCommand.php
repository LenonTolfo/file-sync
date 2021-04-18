<?php
/**
 * @file
 * SyncFileCommand.php
 *
 * @author: Lenon Tolfo <lenon.tolfo@de-media.de>
 */

namespace App\Command;

use App\Controller\SyncController;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncFileCommand extends Command {

  private $syncController;

  protected static $defaultName = 'app:datafile:sync';

  public function __construct(SyncController $syncController) {
    $this->syncController = $syncController;

    parent::__construct();
  }

  protected function configure() {
    $this->setDescription('Sync data from json file into the database');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $result = $this->syncController->syncData();

    if($result['error']){
      $io->error($result['message']);
      return 1;
    }

    $io->listing($result['message']);
    $io->success('Process completed successfully');
    return 0;
  }
}
