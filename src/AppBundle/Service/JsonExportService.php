<?php


namespace AppBundle\Service;


use AppBundle\Entity\Product;
use Psr\Log\LoggerInterface;

class JsonExportService
{
    //Path to file with json export
    private $path;
    private $logger;

    /**
     * JsonExportService constructor.
     * @param $path
     * @param $logger
     */
    public function __construct($path, LoggerInterface $logger)
    {
        $this->path = $path;
        $this->logger = $logger;
    }

    public function export(Product $product)
    {
        $arr = [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'price' => $product->getPrice(),
            'category_name' => $product->getCategory()
        ];

        $json = json_encode($arr);

        if (!file_exists($this->path)){
            mkdir($this->path);
        }

        $res = file_put_contents(
            $this->path .'/'. $product->getId() .'.json',
            $json
        );

        if (!$res){
            $this->logger->critical('Export error');
            return false;
        }

        $this->logger->info('Export success');

        return true;

    }

}