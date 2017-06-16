=== WooCommerce Steem ===
Contributors: recrypto
Donate link: https://steemit.com/@recrypto/
Tags: woocommerce, woo commerce, payment method, steem, sbd
Requires at least: 4.1
Tested up to: 4.7.5
Stable tag: 1.0.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

WooCommerce Steem lets you accept Steem payments directly to your WooCommerce shop (Currencies: STEEM, SBD).

== Description ==

WooCommerce Steem lets you accept Steem payments directly to your WooCommerce shop (Currencies: STEEM, SBD).

With endless flexibility and access to hundreds of free and premium WordPress extensions, WooCommerce now powers 30% of all online stores -- more than any other platform.

= What is Steem? =
[Steem](https://steem.io/) is a blockchain-based social media platform where anyone can earn rewards. An example platform built on top of Steem block chain is [Steemit](steemit.com/).

[youtube https://www.youtube.com/watch?v=xZmpCAqD7hs]

= What is Cryptocurrency? =
A cryptocurrency (or crypto currency) is a digital asset designed to work as a medium of exchange using cryptography to secure the transactions and to control the creation of additional units of the currency. [Wikipedia](https://en.wikipedia.org/wiki/Cryptocurrency)

= Advantages =
You will _NOT_ require any Steem keys for this plugin to work. You just have to provide your Steem username and you're good to go.

= Limitations =
- Currently supports different fiat currencies such as: AUD, BGN, BRL, CAD, CHF, CNY, CZK, DKK, GBP, HKD, HRK, HUF, IDR, ILS, INR, JPY, KRW, MXN, MYR, NOK, NZD, PHP, PLN, RON, RUB, SEK, SGD, THB, TRY, ZAR, EUR
- If none of the fiat currency listed above, it will default 1:1 conversion rate.

= How does it confirm Steem Transfers? =
It uses WordPress CRON every 5 minutes to call WooCommerce orders that uses payment method as Steem and calls an API via Steemful (Another application I'm building around WordPress ecosystem) powered by SteemSQL.


== Installation ==

= Minimum Requirements =

* PHP version 5.2.4 or greater (PHP 5.6 or greater is recommended)
* MySQL version 5.0 or greater (MySQL 5.6 or greater is recommended)
* Some payment gateways require fsockopen support (for IPN access)
* Requires WooCommerce 2.5 requires WordPress 4.1+

Visit the [WooCommerce server requirements documentation](https://docs.woocommerce.com/document/server-requirements/) for a detailed list of server requirements.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of WooCommerce Steem, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "WooCommerce Steem" and click Search Plugins. Once you've found our eCommerce plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our eCommerce plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Frequently Asked Questions ==

= Where can I get support or talk to other users? =

If you get stuck, you can ask for help in the [WooCommerce Plugin Forum](https://wordpress.org/support/plugin/woo-steem).

= Where can I report bugs or contribute to the project? =

Bugs can be reported either in our support forum or preferably on the [WooCommerce GitHub repository](https://github.com/recrypto/woocommerce-steem/issues).

= How can I contribute? =

Yes you can! Join in on our [GitHub repository](https://github.com/recrypto/woocommerce-steem/) :)


== Screenshots ==

1. Selecting a Payment Method (Frontend)
2. Steem Payment Details (Frontend)
3. Steem Transaction Details (Frontend)
4. WooCommerce Settings (Backend)
5. Showing an "insightful" prices for accepted currencies in SBD and/or STEEM based on product price (Frontend)
6. Showing an "insightful" prices for accepted currencies in SBD and/or STEEM based on product price on sale (Frontend)
7. Showing an "insightful" prices for accepted currencies in SBD and/or STEEM based on product price as variation (Frontend)
8. Showing an "insightful" prices for accepted currencies in SBD and/or STEEM based on product price as variation on sale (Frontend)


== Changelog ==

= 1.0.2 - 2017-06-03 =
* Initial version in WordPress Plugin Repository

= 1.0.3 - 2017-06-11 =
* Fixed Steem Transaction Transfer data in WooCommerce Order notes in admin
* Fixed date format issue in WooCommerce Order page

= 1.0.4 - 2017-06-16 =
* Added an insightful prices on product templates that shows the accepted currencies such as SBD and/or STEEM rates converted from the product price


== Upgrade Notice ==

= 1.0.4 - 2017-06-16 =
* Added an insightful prices on product templates that shows the accepted currencies such as SBD and/or STEEM rates converted from the product price