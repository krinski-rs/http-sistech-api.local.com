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
    protected $name = 'network.marca_switch';
    
    /**
     * {@inheritdoc}
     */
    protected static $choices = [
        self::LINKSYS,
        self::EXTREME,
        self::DATACOM,
        self::CISCO
    ];
}
