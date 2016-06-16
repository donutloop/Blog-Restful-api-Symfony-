<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Library;

/**
 * Class ViewData
 * @package AppBundle\Library
 */
class ViewData {

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var integer
     */
    private $code;

    /**
     * @var array
     */
    private $errors;

    /**
     * @var array
     */
    private $warnings;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $queryParam;
    
    /**
     * ViewData constructor.
     * @param $data
     * @param int $code
     * @param string $message
     * @param array $queryParam
     * @param array $errors
     * @param array $warnings
     */
    public function __construct(int $code, $data = null, array $queryParam = [], string $message = '', array $errors = [], array $warnings = []) {
      $this->code = $code;
      $this->data = $data;
      $this->message = $message;
      $this->queryParam = $queryParam;
      $this->errors = $errors;
      $this->warnings = $warnings;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data) {
       $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function setCode(int $code) {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int {
        return $this->code;
    }

    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors) {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * @param array $warnings
     * @return $this
     */
    public function setWarnings(array $warnings) {
        $this->warnings = $warnings;
        return $this;
    }

    /**
     * @return array
     */
    public function getWarnings(): array {
        return $this->warnings;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message) {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param array $queryParam
     * @return $this
     */
    public function setQueryParam(array $queryParam) {
        $this->queryParam = $queryParam;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParam(): array {
        return $this->queryParam;
    }
}