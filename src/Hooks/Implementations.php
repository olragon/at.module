<?php

namespace Drupal\at\Hooks;

class Implementations
{

    /** @var HookMenu */
    private $hookMenu;

    public function getHookMenu()
    {
        if (NULL === $this->hookMenu) {
            $this->hookMenu = new HookMenu(at()->getContainer()->get('yaml.parser'));
        }
        return $this->hookMenu;
    }

    public function setHookMenu($hookMenu)
    {
        $this->hookMenu = $hookMenu;
        return $this;
    }

}
