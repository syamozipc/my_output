<?php

namespace App\interface;

interface EmailTokenInterface
{
    public function sendEmail();
    public function verifyToken();
}