<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\InternationalPhoneNumber;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use PHPUnit\Framework\TestCase;

class InternationalPhoneNumberTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            InternationalPhoneNumber::class,
            'InternationalPhoneNumber-post',
            [
                'type' => 'string',
                'format' => 'internationalphonenumber',
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(InternationalPhoneNumber::class);
    }
}
