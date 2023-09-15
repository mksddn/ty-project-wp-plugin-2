=== TYLR Player ===
Contributors: Ty Project
Tags: TYLR Player
Requires at least: 5.3
Tested up to: 6.2
Requires PHP: 7.0
Stable tag: 0.2.16
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Gutenberg Sidebar for TYLR Player

== Description ==

TYLR (Tyler) is a free and easy solution for publishers to turn text into audio content for everyone to enjoy.

**Important!** TYLR Player WP Plugin requires Gutenberg Editor.

== Changelog ==

= 0.2.2 (2023-05-10): =
- Adding info about the player type to DB and to the admin columns

= 0.2.3 (2023-05-11): =
- Disabling the position choice if is dynamic player + update the position when change the type (ONLY FOR SIDEBAR)

= 0.2.4 (2023-05-12): =
- Fixed display the player info in columns for pages
- Updating the player type using quick edit

= 0.2.5 (2023-05-16): =
- Updating the player type using bulk edit
- Hide the position field if new player type is dynamic (for quick/bulk edit)
- Hide the position field if old player type is dynamic (for quick edit)

= 0.2.6 (2023-05-22): =
- Added new player positions (after 1-2 paragraph, after title)

= 0.2.7 (2023-05-23): =
- Added buttons for choosing player type

= 0.2.8 (2023-05-26): =
- Added a check if this is a post/page edit page. In order not to break the sidebar for 'template' post type etc.

= 0.2.9 (2023-05-29): =
- Now you can delete a player
- Fixed some styles and scripts logic

= 0.2.10 (2023-05-31): =
- Solved the problem with overwriting values via bulk editing
- Sorting the players by their type in quick/bulk edit selector

= 0.2.11 (2023-06-01): =
- Fixed the bug with the extra dynamic player margins
- Fixed not showing a dynamic player in case a player position doesn't set

= 0.2.12 (2023-06-14): =
- Changed the style of the buttons "Add a Dynamic Player" and "Add a Static Player" to radio buttons on the edit page
- Fixed: After quick/bulk editing, the dynamic player had a position in the table
- Added the functionality to "Delete the current Player" on the quick/bulk edit block

= 0.2.13 (2023-06-15): =
- Fixed the bug with deleting the current player via quick/bulk editing

= 0.2.14 (2023-08-02): =
- Fixed the bug with double player initialization

= 0.2.15 (2023-08-04): =
- Prohibition Direct File Access to plugin files
- Generic function/class/define/namespace/option names
- Variables and options are escaped when echo'd now

= 0.2.16 (2023-08-07): =
- The data is sanitized, escaped, and validated now

= 0.2.17 (2023-09-15): =
- Additional checking of internal variables
- The widget rendering logic has been moved from php to js
- Variables and options are escaped when echo'd now
- Clean code comments