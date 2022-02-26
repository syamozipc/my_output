<?php

namespace App\interface;

interface EmailTokenInterface
{
    public function sendEmail():void;
    public function verifyToken():void;
}