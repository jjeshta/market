<?php

/**
 * @file
 * Provides \Drupal\sandwich\SandwichInterface.
 *
 * When defining a new plugin type you need to define an interface that all
 * plugins of the new type will implement. This ensures that consumers of the
 * plugin type have a consistent way of accessing the plugin's functionality. It
 * should include access to any public properties, and methods for accomplishing
 * whatever business logic anyone accessing the plugin might want to use.
 *
 * For example, an image manipulation plugin might have a "process" method that
 * takes a known input, probably an image file, and returns the processed
 * version of the file.
 *
 * In our case we'll define methods for accessing the human readable description
 * of a sandwich and the number of calories per serving. As well as a method for
 * ordering a sandwich.
 */

namespace Drupal\sandwich;

/**
 * An interface for all Sandwich type plugins.
 */
interface SandwichInterface
{

/**
 * Provide a description of the sandwich.
 *
 * @return string
 * A string description of the sandwich.
 */
    public function description();

/**
 * Provide the number of calories per serving for the sandwich.
 *
 * @return float
 * The number of calories per serving.
 */
    public function calories();

/**
 * @param array $extras
 * An array of extra ingredients to include with this sandwich.
 *
 * @return mixed
 */
    public function order(array $extras);
}