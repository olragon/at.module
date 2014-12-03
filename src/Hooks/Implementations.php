<?php

namespace Drupal\at\Hooks;

class Implementations
{

    /** @var HookMenu */
    private $hookMenu;

    public function getHookMenu()
    {
        if (NULL === $this->hookMenu) {
            $parser = at()->get('yaml.parser');
            $validator = at()->get('json_schema.validator');
            $moduleAPI = at()->getModuleAPI();
            $this->hookMenu = new HookMenu($parser, $validator, $moduleAPI);
        }
        return $this->hookMenu;
    }

    public function setHookMenu($hookMenu)
    {
        $this->hookMenu = $hookMenu;
        return $this;
    }

}
