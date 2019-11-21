<?php
namespace App\DBAL\Type\Enum\Network;
use App\DBAL\Type\Enum\AbstractEnumType;
final class StatusVlanType extends AbstractEnumType
{
    const LIVRE         = 'LIVRE';
    const EM_USO        = 'EM USO';
    const RESERVADA     = 'RESERVADA';
    const TEMPORARIA    = 'TEMPORÁRIA';
    
    /**
     * {@inheritdoc}
     */
    protected $name = 'network.status_vlan';
    
    /**
     * {@inheritdoc}
     */
    protected static $choices = [
        self::LIVRE,
        self::EM_USO,
        self::RESERVADA,
        self::TEMPORARIA
    ];
}