<?php

namespace common\modules\cms;

interface SectionInterface {
    
    public function setPage($page);
    public function renderSection($sectionOpen = false);
}

