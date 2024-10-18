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
    $io = new SymfonyStyle($input, $output);

    $sourceDirs = [
      __DIR__ . '/../controllers',
      __DIR__ . '/../helpers',
      __DIR__ . '/../models',
      __DIR__ . '/../views/auth',
    ];
    $targetDirs = [
      __DIR__ . '/../../../../../application/controllers',
      __DIR__ . '/../../../../../application/helpers',
      __DIR__ . '/../../../../../application/models',
      __DIR__ . '/../../../../../application/views/auth',
    ];

    $i = 0;
    foreach ($sourceDirs as $sourceDir) {
      $targetDir = $targetDirs[$i];
      $this->copyFiles($io, $sourceDir, $targetDir);
      $i++;
    }

    $io->success('All files copied successfully.');
    return Command::SUCCESS;
  }

  function copyFiles($io, $sourceDir, $targetDir)
  {
    $filesystem = new Filesystem();

    if (!is_dir($sourceDir)) {
      $io->error("Source directory $sourceDir does not exist.");
      return Command::FAILURE;
    }

    if (!is_dir($targetDir)) {
      $filesystem->mkdir($targetDir, 0755);
    }

    $files = new \FilesystemIterator($sourceDir, \FilesystemIterator::SKIP_DOTS);
    foreach ($files as $file) {
      $targetPath = $targetDir . '/' . $file->getFilename();
      $filesystem->copy($file->getPathname(), $targetPath, true);
      $io->success("Copied {$file->getFilename()} to $targetDir");
    }
  }
}
