MyTicket Events
Contributors: kenzap
Tags: event, performance, calendar, concerts, ticketing, PDF
Requires at least: 5.0
Tested up to: 5.2
Stable tag: 1.1.2
Donate link: https://kenzap.com/wordpress-plugin-donation-page/
Requires PHP: 5.6
License: GPL2+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

== Description ==

A beautiful and easy customizable set of Gutenberg blocks to list events, create calendars and generate QR-code PDF tickets. The plugin extends WooCommerce plugin functionality by creating additional fields under products section, provides seamless checkout experience and support of more than 100+ payment gateways. This solution should help you sell tickets without a hassle out of the box.


The plugin includes:

- Event listing with keyword, category, price, location and date filtering
- Event calendar with carousel
- Large call to action ticket add buttons 
- Custom WooCommerce checkout page with QR-code PDF ticket download
- Extra WooCommerce product fields like date, venue, location to transform products into events
- Secure MyTicket Scanner android application for QR-code ticket validation
- Customizable email and PDF templates


== Installation ==

This plugin can be installed directly from your site.

1. Log in and navigate to _Plugins &rarr; Add New_.
2. Type "MyTicket Events” into the Search and hit Enter.
3. Locate the MyTicket Events plugin in the list of search results and click **Install Now**.
4. Once installed, click the Activate link.
5. Go to Pages > Add New > Find MyTicket Listing block.
6. Adjust Container > Max width setting if elements are not displayed properly. 


It can also be installed manually.

1. Download the MyTicket Events plugin from WordPress.org.
2. Unzip the package and move to your plugins directory.
3. Log into WordPress and navigate to the Plugins screen.
4. Locate MyTicket Events in the list and click the *Activate* link.
5. Go to Pages > Add New > Find MyTicket Listing block.
6. Adjust Container > Max width setting if elements are not displayed properly. 

== Frequently Asked Questions ==

1. What is this plugin for?
To extend your current theme with ticket selling functionality

2. Why the block is not properly alight?
Under Inspect Control find Container > Max width setting. Reduce size in order to fit content to your theme’s viewport properly.

3. How to customize PDF templates?
Under your theme root folder create myticket-events folder and copy templates from my ticket-events plugin templates folder.

4. How to validate tickets?
To validate tickets you need MyTicket Scanner application that you can download from Play Market. Scan tickets and validate with the help of an app. Ticket status updates are reflected immediately under WooCommerce orders section. 

5. How to view additional plugin settings?
Go to Appearance > Customizer > MyTicket section. Set up general ticketing settings including email notifications, mobile app passwords and check page style

6. Why choose this plugin?
Easy to start selling tickets, woocommerce integrated, various event listings, customizable PDF tickets, email notifications, Android app tested by 50,000 people, it is freshly created for latest WordPress Gutenberg editor.

7. How to create hall, stadium layout with seat selection
This link covers basic instructions on how to generate your first seta layout: http://kenzap.com/how-to-integrate-concert-hall-seat-layout-into-wordpress/
You can also view live preview yourself here: https://kenzap.com/blocks/myticket-events-5-1/

8. Why ticket download after checkout requires sign in?
To change this settings go to WooCommerce and untick this setting “Allow customers to place orders without an account” available under Accounts & Privacy tab

9. How to add Chinese character support?
Copy ../mpdf/ttfonts/Sun-ExtA.ttf
Copy ../mpdf/ttfonts/Sun-ExtB.ttf
Under ../ticket-general/index.php add to html tag: style="font-family:sun-extA;"

== Upgrade Notice ==

This is a first release

== Screenshots ==
1. Event listing
2. Event calendar
3. Call to Action buttons
4. General QR-code template
5. Individual QR-code template
6. Checkout page
7. Seat layout example
8. Concert hall/stadium layout example 
9. MyTicket Listing 5

== Changelog ==

= v1.0.0 =
New: Initial release

= v1.0.1 =
New: new layout type added “MyTicket Listing 4”
New: Image Aspect Ratio setting for block “MyTicket Listing 2”
Update: undefined index check notice removed
Update: minor design and different theme compatibility improvements

= v1.1.0 =
New: new layout type added “MyTicket Hall”. For stadium, hall seat mapping.
Update: TGM-Plugin-Activation updated to latest version

= v1.1.1 =
New: new layout type added “MyTicket Listing 5” added.
Update: Category filtering improved for “MyTicket Listing 1”
Update: Border radius styling setting added for “MyTicket Listing 1”
Update: Chrome browser blue outline removed of checkbox filters
Update: Text coloring improved for “MyTicket Listing 1”
Update: Redirect setting added for “MyTicket Listing 3”
Update: Added option to change slider currency symbol for “MyTicket Listing 1”

= v1.1.2 =
Update: overall block customization preview improved
Update: woocommerce myaccount view pdf button added.

= v1.1.3 =
Update: price range filter visual adjustments “MyTicket Listing 1”
Update: accidental refresh on mobile devices bug fix “MyTicket Listing 1”
New: past and upcoming setting added for “MyTicket Listing 1”
Update: Pagination migrated to Ajax in “MyTicket Listing 1”
Update: Date sorting issues fixed “MyTicket Listing 4”

= v1.1.4 =
Update: thank you page issues solved
Update: cart/checkout email name validation added
Update: multiple ticket download upon checkout page now fixed
Update: myticket customizer settings updated
Update: verified issue with restricted app IDs for MyTicket Scanner app 
Update: fixed bug with CPU overload and multiple queries
