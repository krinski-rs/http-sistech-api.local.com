<?php
namespace App\DBAL\Type\Enum\Network;
use App\DBAL\Type\Enum\AbstractEnumType;
final class PortTypeType extends AbstractEnumType
{
    const FE    = 'FE';
    const GE    = 'GE';
    const GE10  = 'GE10';
    
    /**
     * {@inheritdoc}
     */
    protected $name = 'network.port_type';
    
    /**
     * {@inheritdoc}
     */
    protected static $choices = [
        self::FE,
        self::GE,
        self::GE10
    ];
}