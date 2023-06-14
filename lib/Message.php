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

class Message
{
    public $content = "";
    public $username = "";
    public $avatarUrl = "";
    public $embeds = array();

    public function content($content)
    {
        $this->content = trim($content);
        return $this;
    }

    public function username($username)
    {
        $this->username = trim($username);
        return $this;
    }

    public function avatarUrl($avatarUrl)
    {
        $this->avatarUrl = trim($avatarUrl);
        return $this;
    }

    public function embed($embed)
    {
        $this->embeds[] = $embed;
        return $this;
    }

    public function toArray()
    {

        $message = array();

        if (!empty($this->content)) {
            $message["content"] = $this->content;
        }    
        if (!empty($this->avatarUrl)) {
            $message["avatar_url"] = $this->avatarUrl;
        }
        if (!empty($this->username)) {
            $message["username"] = $this->username;
        }
        if (!empty($this->embeds)) {
            $message["embeds"] = $this->embeds;
        }

        return $message;
    }
}