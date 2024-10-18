<?php

namespace CodeIgniter3\Auth\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallCommand extends Command
{
  protected static $defaultName = 'install:codeigniter3-auth';

  protected function configure()
  {
    $this
      ->setName('install:codeigniter3-auth')
      ->setDescription('');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    // Create an instance of SymfonyStyle for pretty output
    $io = new SymfonyStyle($input, $output);

    // Define the source and target directories
    $sourceDir = __DIR__ . '/../controllers'; // Adjust this if the path is different
    $targetDir = __DIR__ . '/../../../../../../application/controllers';

    // Create a Filesystem instance for file operations
    $filesystem = new Filesystem();

    // Check if the source directory exists
    if (!is_dir($sourceDir)) {
      $io->error("Source directory $sourceDir does not exist.");
      return Command::FAILURE;
    }

    // Create the target directory if it doesn't exist
    if (!is_dir($targetDir)) {
      $filesystem->mkdir($targetDir, 0755);
    }

    // Iterate over files in the source directory and copy them to the target directory
    $files = new \FilesystemIterator($sourceDir, \FilesystemIterator::SKIP_DOTS);
    foreach ($files as $file) {
      $targetPath = $targetDir . '/' . $file->getFilename();
      $filesystem->copy($file->getPathname(), $targetPath, true);
      $io->success("Copied {$file->getFilename()} to $targetDir");
    }

    // Return success if all files are copied
    $io->success('All files copied successfully.');
    return Command::SUCCESS;
  }
}
