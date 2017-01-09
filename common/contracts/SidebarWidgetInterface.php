<?php
namespace common\contracts;

interface SidebarWidgetInterface {
    
    public function getPageWidgets($except = []): array;
    public function getPageWidget(string $name): string;
}

