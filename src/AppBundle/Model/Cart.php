<?php


namespace AppBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;

class Cart
{
    private $items;

    /**
     * Cart constructor.
     * @param $items
     */
    public function __construct(array $items)
    {
        $this->items = new ArrayCollection($items);
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->items as $item){
            $total += $item->getPrice();
        }

        return $total;
    }
}