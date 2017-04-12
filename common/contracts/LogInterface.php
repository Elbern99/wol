<?php

namespace common\contracts;

interface LogInterface {
    
    public function addLine(string $line);
    public function getLog(): array;
    public function getCount(): int;
}
