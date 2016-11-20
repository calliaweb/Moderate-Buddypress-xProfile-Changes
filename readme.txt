# Moderate-Buddypress-xProfile-Changes
Moderate BuddyPress xProfile changes by using a moderated visibility levels.

## Description
This plugin uses Visiibility Levels to moderate profile changes.
It creates a new 'moderated' visibility level which is only visible to admin.
When a user saves their profile it sets all their profile fields
visibility level to moderated and an email is sent to the site admin.
The site admin then needs to view the profile and just click save to
set all the fields visibility levels back to thier defaults.

## Requirements

BuddyPress 1.6+

Profile fields must be set to 'Allow members to override' and not 'Enforce field visibility'

## Installation

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Using git, browse to your /wp-content/plugins/ directory and clone this repository:

git clone git@github.com:calliaweb/Moderate-Buddypress-xProfile-Changes.git

Then go to your Plugins screen and click Activate.

## Frequently Asked Questions

### Does this moderate new user registrations?
Not at this time

### Does this moderate profile avatar and cover image changes?
Not at this time

### Users cannot alter their profile field visibility even though 'Allow members to override' is set
This is by design. After moderation, this plugin resets the profile field visibility back to the field defaults which would overwrite a user's custom setting. Therefore the option for users to default a custom visibilty is removed.


## Changelog
### 0.0.2
* Remove the override on visibility capability as this stops the default field visibility being set on new user registration. Replace with the removal of the profile visibility setting page on user profiles.

### 0.0.1
* initial release on GH

