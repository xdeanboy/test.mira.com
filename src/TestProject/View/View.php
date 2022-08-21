<?php

namespace TestProject\View;

class View
{
    private $templatePatch;
    private $extraVars = [];

    /**
     * @param string $templatePatch
     */
    public function __construct(string $templatePatch)
    {
        $this->templatePatch = $templatePatch;
    }

    /**
     * @param string $name
     * @param $value
     * @return void
     */
    public function setVars(string $name, $value)
    {
        $this->extraVars[$name] = $value;
    }

    /**
     * @param string $templateName
     * @param array $vars
     * @param int $code
     * @return void
     */
    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code);

        extract($this->extraVars);
        extract($vars);

        ob_start();
        include $this->templatePatch . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }
}