<?php

namespace AndyTruong\Event;

use RuntimeException;
use Symfony\Component\EventDispatcher\GenericEvent;

class Event extends GenericEvent
{

    /** @var array Collect results */
    protected $results = [];

    /** @var array */
    protected $result_validators = [];

    /**
     * Add result validator.
     *
     * @param callable $validator
     */
    public function addResultValidator(callable $validator)
    {
        $this->result_validators[] = $validator;
    }

    /**
     * Collect result.
     *
     * @param mixed $result
     * @throws Exception
     */
    public function addResult($result)
    {
        try {
            foreach ($this->result_validators as $validator) {
                call_user_func($validator, $result);
            }
        }
        catch (\Exception $e) {
            throw new RuntimeException('Invalid value: ' . $e->getMessage());
        }

        $this->results[] = $result;
    }

    public function getResults()
    {
        return $this->results;
    }

}
