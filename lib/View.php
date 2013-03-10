<?php
//dengjing34@vip.qq.com

class View {
	protected $view_filename = false;
	public $name = null;

	protected $data = array();

	private $render = null;

	public function __construct($name, $data = NULL, $type = NULL) {
		$this->view_filename = BASEDIR . "view/{$name}.php";
		$this->name = $name;

		if (is_array($data) AND ! empty($data)) {
			// Preload data using array_merge, to allow user extensions
			$this->data = array_merge($this->data, $data);
		}
	}

	/**
	 * Sets a view variable.
	 *
	 * @param   string|array  name of variable or an array of variables
	 * @param   mixed         value when using a named variable
	 * @return  object
	 */
	public function set($name, $value = NULL) {
		if (func_num_args() === 1 AND is_array($name)) {
			foreach($name as $key => $value) {
				$this->__set($key, $value);
			}
		} else {
			$this->__set($name, $value);
		}
		return $this;
	}

	/**
	 * Sets a bound variable by reference.
	 *
	 * @param   string   name of variable
	 * @param   mixed    variable to assign by reference
	 * @return  object
	 */
	public function bind($name, & $var) {
		$this->data[$name] =& $var;

		return $this;
	}

	/**
	 * Magically sets a view variable.
	 *
	 * @param   string   variable key
	 * @param   string   variable value
	 * @return  void
	 */
	public function __set($key, $value) {
		if (!isset($this->$key)) {
			$this->data[$key] = $value;
		}
	}

	/**
	 * Magically gets a view variable.
	 *
	 * @param  string  variable key
	 * @return mixed   variable value if the key is found
	 * @return void    if the key is not found
	 */
	public function __get($key) {
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}
	}

	/**
	 * Magically converts view object to string.
	 *
	 * @return  string
	 */
	public function __toString() {
		if ($this->render !== null) return $this->render;
		try {
			return $this->render();
		} catch (Exception $e) {
			trigger_error($e->getMessage(), E_USER_WARNING);
		}
	}

	/**
	 * Renders a view.
	 *
	 * @param   boolean   set to TRUE to echo the output instead of returning it
	 * @return  string    if print is FALSE
	 * @return  void      if print is TRUE
	 */
	public function render($print = FALSE) {
		// Buffering on
		ob_start();

		// Import the view variables to local namespace
		extract($this->data, EXTR_SKIP);

		// Views are straight HTML pages with embedded PHP, so importing them
		// this way insures that $this can be accessed as if the user was in
		// the controller, which gives the easiest access to libraries in views
		include $this->view_filename;

		// Fetch the output and close the buffer
		$this->render = ob_get_clean();

		if ($print == false) return $this->render;
		echo $this->render;
	}

}