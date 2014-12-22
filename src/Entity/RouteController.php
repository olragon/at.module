<?php

namespace Drupal\at\Entity;

use EntityAPIControllerExportable;

class RouteController extends EntityAPIControllerExportable
{

    public function buildContent($route, $view_mode = 'full', $langcode = NULL, $content = array())
    {
        $output = parent::buildContent($route, $view_mode, $langcode, $content);

        // @TODO: extra fields info…
        $output['at_page'] = $route->render();
        if (is_string($output['at_page'])) {
            $output['at_page'] = ['#markup' => $output['at_page']];
        }

        return $output;
    }

}
