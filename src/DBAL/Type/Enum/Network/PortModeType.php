<?php
namespace App\DBAL\Type\Enum\Network;
use App\DBAL\Type\Enum\AbstractEnumType;
final class PortModeType extends AbstractEnumType
{
    const ACCESS        = 'ACCESS';
    const TRUNK         = 'TRUNK';
    const DYNAMIC       = 'DYNAMIC';
    const DESIRABLE     = 'DESIRABLE';
    const AUTO_MODES    = 'AUTO MODES';
    
    /**
     * {@inheritdoc}
     */
    protected $name = 'network.port_mode';
    
    /**
     * {@inheritdoc}
     */
    protected static $choices = [
        self::ACCESS,
        self::TRUNK,
        self::DYNAMIC,
        self::DESIRABLE,
        self::AUTO_MODES
    ];
}