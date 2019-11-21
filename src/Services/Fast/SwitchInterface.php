<?php
namespace App\Services\Fast;
interface SwitchInterface
{
    public function getIfAlias();
    public function getIfAdminStatus();
    public function getIfOperStatus();
    public function getIfDuplexStatus();
    public function getIfSpeed();
}