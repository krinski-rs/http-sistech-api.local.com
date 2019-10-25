<?php
namespace App\DBAL\Type\Enum\Network;
use App\DBAL\Type\Enum\AbstractEnumType;
final class MarcaSwitchType extends AbstractEnumType
{
    const LINKSYS   = 'Linksys';
    const EXTREME   = 'Extreme';
    const DATACOM   = 'Datacom';
    const CISCO     = 'Cisco';
    
    /**
     * {@inheritdoc}
     */
    protected $name = 'redes.marca_switch';
    
    /**
     * {@inheritdoc}
     */
    protected static $choices = [
        0 => self::LINKSYS,
        1 => self::EXTREME,
        2 => self::DATACOM,
        3 => self::CISCO
    ];
}
