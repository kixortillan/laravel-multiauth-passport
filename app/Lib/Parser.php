<?php

namespace App\Lib;

class Parser
{

    protected $searchResult = [];

    public function search($keys, $obj)
    {
        $this->dig($keys, $obj);

        return $this->searchResult;
    }

    private function dig($keys, $obj)
    {
        foreach (get_object_vars($obj) as $key => $val)
        {
            if (is_object($val))
            {
                $this->dig($keys, $val);
            }

            if (in_array($key, $keys))
            {
                $this->searchResult[$key] = $val;
            }
        }
    }

}
