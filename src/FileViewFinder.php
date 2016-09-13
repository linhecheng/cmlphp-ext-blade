<?php

namespace CmlExt\Blade;

use Cml\Lang;
use InvalidArgumentException;

class FileViewFinder
{
    /**
     * The array of active view paths.
     *
     * @var array
     */
    protected $paths;

    /**
     * The array of views that have been located.
     *
     * @var array
     */
    protected $views = [];

    /**
     * Register a view extension with the finder.
     *
     * @var array
     */
    protected $extensions = ['blade.php', 'php'];

    /**
     * Create a new file view loader instance.
     *
     * @param  array  $paths
     * @param  array  $extensions
     */
    public function __construct(array $paths, array $extensions = null)
    {
        $this->paths = $paths;

        if (isset($extensions)) {
            $this->extensions = $extensions;
        }
    }

    /**
     * Get the fully qualified location of the view.
     *
     * @param  string  $name
     * @return string
     */
    public function find($name)
    {
        if (isset($this->views[$name])) {
            return $this->views[$name];
        }

        return $this->views[$name] = $this->findInPaths($name, $this->paths);
    }

    /**
     * Find the given view in the list of paths.
     *
     * @param  string  $name
     * @param  array   $paths
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function findInPaths($name, $paths)
    {
        foreach ((array) $paths as $path) {
            foreach ($this->getPossibleViewFiles($name) as $file) {
                if (file_exists($viewPath = $path.'/'.$file)) {
                    return $viewPath;
                }
            }
        }

        throw new InvalidArgumentException(Lang::get('_TEMPLATE_FILE_NOT_FOUND_', $name));
    }

    /**
     * Get an array of possible view files.
     *
     * @param  string  $name
     * @return array
     */
    protected function getPossibleViewFiles($name)
    {
        return array_map(function ($extension) use ($name) {
            return str_replace('.', '/', $name).'.'.$extension;

        }, $this->extensions);
    }

    /**
     * Add a location to the finder.
     *
     * @param  string  $location
     * @return void
     */
    public function addLocation($location)
    {
        $this->paths[] = $location;
    }

    /**
     * Register an extension with the view finder.
     *
     * @param  string  $extension
     * @return void
     */
    public function addExtension($extension)
    {
        if (($index = array_search($extension, $this->extensions)) !== false) {
            unset($this->extensions[$index]);
        }

        array_unshift($this->extensions, $extension);
    }


    /**
     * Get the active view paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get registered extensions.
     *
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}
