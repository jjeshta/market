<?php

namespace Drupal\sandwich;

use Drupal\Component\Plugin\PluginBase;

abstract class SandwichBase extends PluginBase implements SandwichInterface
{
    /**
     * Retrieve the @description property from the annotation and return it.
     *
     * @return string
     */
    public function description()
    {
        return $this->pluginDefinition['description'];
    }

    /**
     * Retrieve the @calories property from the annotation and return it.
     *
     * @return float
     */
    public function calories()
    {
        return (float) $this->pluginDefinition['calories'];
    }
}