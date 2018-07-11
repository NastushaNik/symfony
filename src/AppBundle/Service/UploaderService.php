<?php
namespace AppBundle\Service;
use Monolog\Logger;
use Symfony\Bridge\Doctrine\RegistryInterface;
class UploaderService
{
	private $projectPath;
	private $uploadDir;
	private $logger;
	private $doctrine;
	public function __construct($projectPath, $uploadDir, Logger $logger, RegistryInterface $doctrine)
	{
		$this->projectPath = $projectPath;
		$this->uploadDir = $uploadDir;
		$this->logger = $logger;
		$this->doctrine;
	}
	public function upload()
	{
		$this->logger->info('Upload');
		return $this->getPath();
	}
	private function getPath()
	{
		// use param!!
		return $this->projectPath
		       . DIRECTORY_SEPARATOR
		       . 'web'
		       . DIRECTORY_SEPARATOR
		       . $this->uploadDir
		;
	}
}