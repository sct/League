<?php

namespace sct\League\Common;

class AggregatedStats
{

	public function __construct($properties)
	{
		foreach ($properties as $key => $value) {
            if (empty($value)) {
                $value = 0;
            }
            $this->{$key} = $value;
        }
	}
}