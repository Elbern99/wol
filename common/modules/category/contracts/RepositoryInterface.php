<?php
namespace common\modules\category\contracts;

interface RepositoryInterface {
    
    public function __construct($currentCategory);
    public function getPageParams();
    public function getTamplate();
}