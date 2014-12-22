<?php

namespace Drupal\at\Entity;

use EntityDefaultExtraFieldsController;

class RouteExtraFieldsController extends EntityDefaultExtraFieldsController
{

    public function fieldExtraFields()
    {
        $extra = array();

        foreach (entity_load('at_route_type') as $route_type) {
            $extra['at_route'][$route_type->type]['display'] = [
                'at_page' => [
                    'label'  => t('Main page content'),
                    'weight' => 10,
                ],
            ];
        }

        return $extra;
    }

}
