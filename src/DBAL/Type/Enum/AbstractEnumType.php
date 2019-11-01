<?php
/**
 * AbstractEnumType.
 *
 * @author Reinaldo Krinski <reinaldo.krinski@gmail.com>
 */
namespace App\DBAL\Type\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\MySqlPlatform;
abstract class AbstractEnumType extends Type
{
    /**
     * @var string $name Nome do Tipo
     */
    protected $name = '';
    
    /**
     * @var array $choices Array dos valores ENUM, onde os valores ENUM são chaves e suas versões legíveis são valores
     * @static
     */
    protected static $choices = [];
    
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $objAbstractPlatform)
    {
        if (null === $value) {
            return null;
        }
        if (!in_array($value, static::$choices)) {
            throw new \InvalidArgumentException(sprintf('Invalid value "%s" for ENUM "%s".', $value, $this->getName()));
        }
        return $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $objAbstractPlatform)
    {
        $values = implode(
            ', ',
            array_map(
                function ($value) {
                    return "'{$value}'";
                },
                static::getValues()
            )
        );
        if ($objAbstractPlatform instanceof SqlitePlatform) {
            return sprintf('TEXT CHECK(%s IN (%s))', $fieldDeclaration['name'], $values);
        }
        if ($objAbstractPlatform instanceof PostgreSqlPlatform || $objAbstractPlatform instanceof SQLServerPlatform || $objAbstractPlatform instanceof MySqlPlatform) {
            return sprintf('VARCHAR(255) CHECK(%s IN (%s))', $fieldDeclaration['name'], $values);
        }
        return sprintf('ENUM(%s)', $values);
    }
    
    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return TRUE;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name ?: (new \ReflectionClass(get_class($this)))->getShortName();
    }
    
    /**
     * Obtenha escolhas legíveis para o campo ENUM.
     * @static
     * @return array Valores para o campo ENUM
     */
    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
    
    /**
     * Obtenha valores para o campo ENUM.
     * @static
     * @return array Valores para o campo ENUM
     */
    public static function getValues()
    {
        return static::$choices;
    }
    
    /**
     * Obtenha valor em formato legível.
     * @param string $value valor ENUM
     * @static
     * @return string|null $value Valor em formato legível
     * @throws \InvalidArgumentException
     */
    public static function getReadableValue($value)
    {
        if (!isset(static::$choices[$value])) {
            throw new \InvalidArgumentException(sprintf('Invalid value "%s" for ENUM type "%s".', $value, get_called_class()));
        }
        return static::$choices[$value];
    }
    
    /**
     * Verifique se existe algum valor de string na matriz de valores ENUM.
     * @param string $value valor ENUM
     * @return bool
     */
    public static function isValueExist($value)
    {
        return isset(static::$choices[$value]);
    }
}