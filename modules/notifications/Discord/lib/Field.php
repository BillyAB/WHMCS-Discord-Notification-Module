<?php

/******************************************************************************
 * Copyright (c) William Beacroft 2023.                                       *
 *                                                                            *
 * @project WHMCS Discord Notifications                                       *
 * @author William Beacroft                                                   *
 * @site https://billyab.co.uk                                                *
 *                                                                            *
 ******************************************************************************/

namespace WHMCS\Module\Notification\Discord;

class Field
{
    public $name = "";
    public $value = "";
    public $inline = true;

    public function name($name)
    {
        $this->name = trim($name);
        return $this;
    }

    public function value($value)
    {
        $this->value = trim($value);
        return $this;
    }

    public function inline($inline)
    {
        $this->inline = (bool) $inline;
        return $this;
    }

    public function toArray()
    {
        $field = [
            'name' => $this->name,
            'value' => $this->value,
            'inline' => $this->inline
        ];
        return $field;
    }
}

?>