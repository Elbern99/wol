<?php
namespace common\modules\blocks;

use common\modules\blocks\contracts\BlockRepositoryInterface;

abstract class AbstractBlock {
    
    protected $repository;
    
    public function __construct(BlockRepositoryInterface $repository) {
        $this->repository = $repository;
    }
    
    abstract function getView():string;
}

