# WHMCS Discord Notifications Module

This module integrates WHMCS with Discord by sending notifications through Discord webhooks. Whenever an event in WHMCS meets the specified criteria, a notification is pushed to a Discord channel. This is particularly useful for keeping your team or community up to date with your WHMCS instance without having to check it directly.

## Features
- Easy configuration through WHMCS admin panel
- Customizable message content and color
- Optional role mention
- Custom webhook avatar
- Uses Discord Webhooks for efficient and easy integration

## Author
- William Beacroft ([billyab.co.uk](https://billyab.co.uk))

## Installation
1. Clone or download this repository.
2. Upload the contents to your WHMCS `modules/notifications` directory.
3. Go to the WHMCS admin panel, navigate to "Setup" -> "Notifications".
4. Activate the Discord Notifications module.
5. Configure the settings accordingly.
6. Create notification rules and configure any override settings accordingly.


## Configuration Settings

#### Discord Webhook URL
- **Type**: text
- **Description**: The Discord Webhook URL that notifications should be sent to.

#### Company Name
- **Type**: text (Optional)
- **Description**: This will be the name of the user that sends the message in the Discord channel.

#### Message Colour
- **Type**: text (Optional)
- **Description**: This is the sideline color for the message. The color code format within this script is standard hex. Exclude the beginning `#` character if one is present.

#### Notification Role ID
- **Type**: text (Optional)
- **Description**: If you'd like to have a specific group pinged on each message, please place the ID here. Only include the numerical ID.

#### Webhook Avatar Image
- **Type**: text (Optional)
- **Description**: Your desired Webhook Avatar. Please make sure you enter a direct link to the image.

## Per Rule Override Settings

### Customise Message
- **Type**: text (Optional)
- **Description**: Allows you to customise the primary display message shown in the notification.

### Discord Webhook URL
- **Type**: text (Optional)
- **Description**: Override the Discord Webhook URL for this notification to send to a different channel.

### Colour
- **Type**: text (Optional)
- **Description**: Override the side line colour for the message. The color code format within this script is standard hex. Exclude the beginning # character if one is present.

## Usage

Once the module is installed and configured, you can set up WHMCS to send notifications based on specific events. For example, you can set it to notify you whenever a new ticket is created, an invoice is paid, etc.

Make sure the webhook URL is set correctly to ensure that notifications are sent to the desired Discord channel.

## Contributing
For bug reports, feature requests, or any other queries regarding this module, please get in touch with the author.

## Disclaimer
This module is provided as-is, and while efforts have been made to ensure its quality, the author is not responsible for any issues that arise from the use of this module. It's important to test this module in a safe environment before deploying it in a production setting.

This module is in no way associated with Discord Inc., its subsidiaries, and its related companies. The Discord logo is copyright Discord Inc.
