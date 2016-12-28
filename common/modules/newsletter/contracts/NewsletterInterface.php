<?php
namespace common\modules\newsletter\contracts;

interface NewsletterInterface {
    
    public function getSubscriber(string $email);
    public function setSubscriber(array $data);
}

