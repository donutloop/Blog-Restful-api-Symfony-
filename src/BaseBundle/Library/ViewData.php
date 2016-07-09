<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Library;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 *
 * Class ViewData
 * @package AppBundle\Library
 *
 * @ExclusionPolicy("all")
 */
class ViewData {

    /**
     * @var mixed
     *
     * @Expose
     * @Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    private $data;

    /**
     * @var integer
     *
     * @Expose
     * @Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    private $code;

    /**
     * @var array
     * @Expose
     * @Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    private $errors;

    /**
     * @var array
     *
     * @Expose
     * @Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    private $warnings;

    /**
     * @var string
     *
     * @Expose
     * @Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    private $message;

    /**
     * @var array
     *
     * @Expose
     * @Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
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