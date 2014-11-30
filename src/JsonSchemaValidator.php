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

    private function retreiveSchema($uri)
    {
        static $schemas = [];

        if (!empty($schemas[$uri])) {
            return $schemas[$uri];
        }

        if (FALSE === strpos($uri, '://')) {
            $uri = "file://{$uri}";
        }

        $schema = $this->uriRetriever->retrieve($uri);
        $this->refResolver->resolve($schema, dirname($uri));

        return $schemas[$uri] = $schema;
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
        $this->check(is_array($value) ? (object) $value : $value, $this->retreiveSchema($schemaUri));
        return $this->isValid();
    }

}
