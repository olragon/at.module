<?php

namespace Drupal\at\Tests\Traits;

trait JsonSchemaTestCaseTrait
{

    public function checkJsonSchema()
    {
        $schemaUri = AT_ROOT . '/misc/fixtures/json_schema/tuple_typing.json';
        $this->doCheckJsonSchema(["tupleTyping" => ["a", 2]], $schemaUri);
    }

    private function doCheckJsonSchema($value, $schemaUri)
    {
        $validator = at()->getJsonSchemaValidator();
        $isValid = $validator->validate($value, $schemaUri);
        $this->assertTrue($isValid);
    }

}
