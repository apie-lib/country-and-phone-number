<?php
namespace Apie\CountryAndPhoneNumber\PropertyGenerators;

use Apie\Core\Persistence\Fields\FieldReference;
use Apie\Core\Persistence\PersistenceFieldInterface;
use Apie\Core\Persistence\PersistenceTableInterface;
use Apie\CountryAndPhoneNumber\CountryAndPhoneNumber;
use Apie\DoctrineEntityConverter\PropertyGenerators\AbstractPropertyGenerator;
use ReflectionNamedType;
use ReflectionProperty;

class CountryAndPhoneNumberPropertyGenerator extends AbstractPropertyGenerator
{
    protected function supportsProperty(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field,
        ReflectionProperty $property
    ): bool {
        if ($field instanceof FieldReference) {
            $type = $field->getProperty()->getType();
            return $type instanceof ReflectionNamedType && $type->getName() === CountryAndPhoneNumber::class;
        }

        return false;
    }

    protected function generateFromCodeConversion(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field,
        ReflectionProperty $property
    ): string {
        assert($field instanceof FieldReference);
        return sprintf(
            '%s::createFrom(["country" => $raw->country, "phoneNumber" => $raw->phoneNumber])',
            $field->getTableReference()
        );
    }

    protected function getTypeForProperty(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field
    ): string {
        assert($field instanceof FieldReference);
        return $field->getTableReference();
    }


    protected function getDoctrineAttribute(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field
    ): string {
        return OneToOne::class;
    }

    protected function getDoctrineAttributeValue(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field
    ): array {
        assert($field instanceof FieldReference);
        return [
            'targetEntity' => $field->getTableReference(),
            'cascade' => ['all'],
        ];
    }

    protected function generateInjectConversion(
        PersistenceTableInterface $table,
        PersistenceFieldInterface $field,
        ReflectionProperty $property
    ): string {
        assert($field instanceof FieldReference);
        $property = $field->getProperty();
        assert($property instanceof ReflectionProperty);
        $declaredClass = $property->getDeclaringClass()->name;
        assert(null !== $declaredClass);
        $declaringClass = 'OriginalDomainObject';
        if ($table->getOriginalClass() !== $declaredClass) {
            $declaringClass = '\\' . $declaredClass;
        }

        return sprintf(
            '$tmp; $tmp->inject(Utils::getProperty($instance, new \ReflectionProperty(%s::class, %s))); $converted = $tmp',
            $declaringClass,
            var_export($property->name, true)
        );
    }
}
