<?php

namespace Drupal\at;

use JsonSchema\Constraints\Constraint;
use JsonSchema\RefResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

class JsonSchemaValidator extends Validator
{

    /** @var RefResolver */
    private $refResolver;

    /** @var UriRetriever */
    protected $uriRetriever;

    public function __construct(UriRetriever $uriRetriever, RefResolver $refResolver)
    {
        parent::__construct(Constraint::CHECK_MODE_NORMAL, $uriRetriever);
        $this->refResolver = $refResolver;
    }

    /**
     * {@inheritdoc}
     * @deprecated Use ::validate()
     */
    public function check($value, $schema = null, $path = null, $i = null)
    {
        return parent::check($value, $schema, $path, $i);
    }

    /**
     * Validate.
     *
     * @param mixed $value
     * @param string $schemaUri
     * @return bool
     */
    public function validate($value, $schemaUri)
    {
        if (FALSE === strpos($schemaUri, '://')) {
            $schemaUri = "file://{$schemaUri}";
        }

        if (is_array($value)) {
            $value = (object) $value;
        }

        $schema = $this->uriRetriever->retrieve($schemaUri);
        $this->refResolver->resolve($schema, dirname($schemaUri));
        $this->check($value, $schema);
        return $this->isValid();
    }

}
