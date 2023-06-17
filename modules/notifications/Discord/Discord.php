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

use WHMCS\Module\Contracts\NotificationModuleInterface;
use WHMCS\Module\Notification\DescriptionTrait;
use WHMCS\Notification\Contracts\NotificationInterface;
use WHMCS\Config\Setting;
use WHMCS\Exception;

/**
 * Notification module for delivering notifications via Discord
 */
class Discord implements NotificationModuleInterface
{
    use DescriptionTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDisplayName('Discord')
            ->setLogoFileName('logo.png');
    }

    /**
     * Settings required for module configuration
     *
     * @return array
     */
    public function settings()
    {
        return [
            'webhookURL' => [
                'FriendlyName' => 'Discord Webhook URL',
                'Type' => 'text',
                'Description' => 'The Discord Webhook URL that notifications should be sent to.',
            ],
            'companyName' => [
                'FriendlyName' => 'Company Name (Optional)',
                'Type' => 'text',
                'Description' => 'This will be the name of the user that sends the message in the Discord channel.',
                'Placeholder' => Setting::getValue('CompanyName'),
            ],
            'discordColor' => [
                'FriendlyName' => 'Message Colour (Optional)',
                'Type' => 'text',
                'Description' => 'This is the side line colour for the message. The color code format within this script is standard hex. Exclude the beginning # character if one is present.',
                'Placeholder' => '73CB0B',
            ],
            'discordGroupID' => [
                'FriendlyName' => 'Notification Role ID (Optional)',
                'Type' => 'text',
                'Description' => 'If you\'d like to have a specific group pinged on each message, please place the ID here. An example of a group ID is: 343029528563548162 (Only include the numerical ID and no other formatting)',
            ],
            'discordWebHookAvatar' => [
                'FriendlyName' => 'Webhook Avatar Image (Optional)',
                'Type' => 'text',
                'Description' => 'Your desired Webhook Avatar. Please make sure you enter a direct link to the image (E.G. https://example.com/iownpaypal.png )',
            ],
        ];
    }

    /**
     * Validate settings for notification module
     *
     * This method will be invoked prior to saving any settings via the UI.
     *
     * @param array $settings Settings present in configuration modal
     *
     * @return boolean
     * @throws Exception webhookURL value is missing
     */
    public function testConnection($settings): bool
    {
        $webhookURL = $settings['webhookURL'];

        if (empty($webhookURL)) {
            throw new Exception("You must provide a webhook URL");
        }
        return true;
    }

    /**
     * The individual customisable settings for a notification.
     *
     * @return array
     */
    public function notificationSettings()
    {
        return [
            'message' => [
                'FriendlyName' => 'Customise Message (Optional)',
                'Type' => 'text',
                'Description' => 'Allows you to customise the primary display message shown in the notification.',
            ],
            'webhookURL' => [
                'FriendlyName' => 'Discord Webhook URL (Optional)',
                'Type' => 'text',
                'Description' => 'Override the Discord Webhook URL for this notification to send to a different channel.',
            ],
            'discordColor' => [
                'FriendlyName' => 'Message Colour (Optional)',
                'Type' => 'text',
                'Description' => 'Override the side line colour for the message. The color code format within this script is standard hex. Exclude the beginning # character if one is present.',
                'Placeholder' => '73CB0B',
            ],
        ];
    }
    
    /**
     * The option values available for a 'dynamic' Type notification setting
     */
    public function getDynamicField($fieldName, $settings)
    {
        return [];
    }

    /**
     * Deliver notification
     *
     * This method is invoked when rule criteria are met.
     *
     * @param NotificationInterface $notification A notification to send
     * @param array $moduleSettings Configured settings of the notification module
     * @param array $notificationSettings Configured notification settings set by the triggered rule
     * 
     */
    public function sendNotification(NotificationInterface $notification, $moduleSettings, $notificationSettings)
    {
        $messageBody = $notification->getMessage();
        if ($notificationSettings["message"]) {
            $messageBody = $notificationSettings["message"];
        }

        if ($notificationSettings["webhookURL"]) {
            $webhookURL = $notificationSettings["webhookURL"];
        } else {
            $webhookURL = $moduleSettings["webhookURL"];
        }

        if ($notificationSettings["discordColor"]){
            $discordColor = hexdec($notificationSettings["discordColor"]);
        } elseif ($moduleSettings["discordColor"]){
            $discordColor = hexdec($moduleSettings["discordColor"]);
        } else { $discordColor = "7588619"; }

        $embed = (new Embed())->title(\WHMCS\Input\Sanitize::decode($notification->getTitle()))
            ->url($notification->getUrl())
            ->description($messageBody)
            ->timestamp(date(\DateTime::ISO8601))
            ->color($discordColor)
            ->footer("WHMCS Discord module by BillyAB");
        foreach ($notification->getAttributes() as $attribute) {
            $value = $attribute->getValue();
            if ($attribute->getUrl()) {
                $value = "[" . $value . "](" . $attribute->getUrl() . ")";
            }
            $embed->addField((new Field())->name($attribute->getLabel())
                ->value($value));
        }


        $companyName = $moduleSettings["companyName"];
        if ($moduleSettings["discordGroupID"]) {
            $discordGroupID = "<@&".$moduleSettings["discordGroupID"].">";
        }
        $discordWebHookAvatar = $moduleSettings["discordWebHookAvatar"];

        $message = (new Message())->content($discordGroupID)
            ->username($companyName)
            ->avatarUrl($discordWebHookAvatar)
            ->embed($embed);

        $this->call($moduleSettings, $webhookURL, $message->toArray());

    }
    protected function call(array $settings, $url, array $postdata = array(), $throwOnError = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        $response = curl_exec($ch);

        $decoded = json_decode($response);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 204) {
            logModuleCall('discord', 'Notification Sending Failed', $postdata, $response, $decoded);
            throw new Exception("An error occurred: " . $response);
        } else {
            logModuleCall('discord', 'Notification Successfully Sent', $postdata, $response, $decoded);
        }
        curl_close($ch);

        return $decoded;
    }

}
