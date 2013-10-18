<?php 

namespace Cain;

use Cain\View;

class Layout
{
	/**
     * variable container for layout
     * @var string
     */
    protected $_container = array();

	/**
     * Layout view file name
     * @var string
     */
    protected $_layout = 'layout';

	/**
     * Array of view path possibilities
     * @var array of strings
     */
	protected $pathStack = array();

    /**
     * default view param name for "content"
     * @var string
     */
	protected $_contentKey = "content";

	 /**
     * suffix for layout script
     * @var string
     */
	protected $_suffix = ".phtml";

	/**
     * Enable template rendering
     * @var Bool
     */
	protected $_enabled = true;

	/**
     * 
     * @var Cain\View
     */
	protected $_view;

	 /**
     * View script suffix for layout script
     * @var string
     */
    protected $_viewSuffix = '.phtml';

    public function __construct($options = null, $initMvc = false)
    {
        if (null !== $options) {
            if (is_string($options)) {
                $this->setScriptPath($options);
            } elseif (is_array($options)) {
                $this->setOptions($options);
            } else {
                throw new Exception('Layout options are invalid');
            }
        }

        $this->_view = new View();
    }

    /**
     * Set optionsfrom array 
     *
     * @param  array $options
     * @return VOID
     */
    public function setOptions($options)
    {
        if (!is_array($options)) {
            throw new Exception('setOptions() expects only an array.');
        }

        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

	public function setScriptPath( $path ) {
		$this->pathStack = $path;
        return $this;
	}

	/**
     * Set layout script to use
     *
     * Note: enables layout by default, can be disabled
     *
     * @param  string $name
     * @param  boolean $enabled
     * @return Cain\Layout
     */
    public function setLayout($name, $enabled = true)
    {
        $this->_layout = (string) $name;
        if ($enabled) {
            $this->enableLayout();
        }
        return $this;
    }

    /**
     * Get current layout script
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * Disable layout
     *
     * @return Cain\Layout
     */
    public function disableLayout()
    {
        $this->_enabled = false;
        return $this;
    }

    public function getView()
    {
        return $this->_view;
    }

    /**
     * Enable layout
     *
     * @return Cain\Layout
     */
    public function enableLayout()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Is layout enabled?
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_enabled;
    }

    /**
     * Set layout script path
     *
     * @param  string $path
     * @return Cain\Layout
     */
    public function setLayoutPath($path)
    {
        return $this->setScriptPath($path);
    }

    /**
     * Get current layout script path
     *
     * @return array
     */
    public function getLayoutPath()
    {
        return $this->getViewScriptPath();
    }

    public function getViewScriptPath()
    {
        return $this->pathStack;
    }

    /**
     * Set content key
     *
     * Key in namespace container denoting default content
     *
     * @param  string $contentKey
     * @return Cain\Layout
     */
    public function setContentKey($contentKey)
    {
        $this->_contentKey = (string) $contentKey;
        return $this;
    }

    /**
     * Retrieve content key
     *
     * @return string
     */
    public function getContentKey()
    {
        return $this->_contentKey;
    }

    /**
     * Set layout variable
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->_container[$key] = $value;
    }

    /**
     * Get layout variable
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->_container[$key])) {
            return $this->_container[$key];
        }

        return null;
    }

    /**
     * Is a layout variable set?
     *
     * @param  string $key
     * @return bool
     */
    public function __isset($key)
    {
        return (isset($this->_container[$key]));
    }

    /**
     * Unset a layout variable?
     *
     * @param  string $key
     * @return void
     */
    public function __unset($key)
    {
        if (isset($this->_container[$key])) {
            unset($this->_container[$key]);
        }
    }

    /**
     * Assign one or more layout variables
     *
     * @param  string
     * @param  mixed $value Value if $spec is a key
     * @return Cain\Layout
     */
    public function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $orig = $this->_container->getArrayCopy();
            $merged = array_merge($orig, $spec);
            $this->_container->exchangeArray($merged);
            return $this;
        }

        if (is_string($spec)) {
            $this->_container[$spec] = $value;
            return $this;
        }
        throw new Exception('Invalid values passed to assign()');
    }

    /**
     * Render layout
     *
     * $name replaces $this->layout as script name.
     *
     * @param  string $name
     * @return mixed|string(Final Html render)
     */
    public function render($name = null)
    {
        if (null === $name)
            $name = $this->getLayout();

        $view = $this->_view;
        if (null !== ($path = $this->getViewScriptPath())) {

                $view->addViewPath($path);

        } elseif (null !== ($path = $this->getViewBasePath())) {
            $view->addBasePath($path, $this->_viewBasePrefix);
        }
        $key = $this->_contentKey;

        $view->$key = $this->$key;
        
        return $view->render($name.$this->_suffix);
    }
}