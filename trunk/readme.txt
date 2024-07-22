=== Concordamos ===
Contributors: itsrio
Tags: quadratic, voting, vote
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 0.6.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Quadratic voting system by Concordamos.

== Description ==

*Quadratic voting* (also known as *plural voting*) is a redesigned voting method reflecting the intensity of people's preferences in collective decisions. Voters receive budgets of credits, which they allocate to different questions on the ballot to signal the intensity of their conviction. Their credits are convertible to "counted votes" according to their square root: one credit equals one vote; four credits equals two votes; nine credits equals three votes, etc. Read more about quadratic voting [here](https://www.radicalxchange.org/concepts/plural-voting/).

This plugin allows the creation of ballots powered by quadratic voting, with much flexibility:

- Configuring the total number of credits each voter receives
- Votings may or may not require registration/login (enforcing one budget per user)
- Voting may or may not allow a negative number of counted votes for any option
- Generate single-use voting links for easily sharing anonymous polls

The plugin is currently available in English and Portuguese.

== Installation ==

1. Upload the entire `concordamos` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the Plugins screen (Plugins > Installed Plugins)

== Screenshots ==

1. List of public votings
2. Voting creation form
3. List of options
4. Voting screen
5. Detailed results page

== Changelog ==

= 0.6.4 =
* Improve rendering of results chart on mobile

= 0.6.2 =
* Fix a bug on the limitation of number of votes
* Fix a bug on the credits calculation in voting panel's chart

= 0.6.1 =
* Bug fixes

= 0.6.0 =
* Allow access to results panel by non-logged users

= 0.5.5 =
* Respect WPML "Hide languages" settings

= 0.5.4 =
* Make text inputs in translation page resizable

= 0.5.0 =
* Make votings translatable via WPML

= 0.4.0 =
* Basic support for WPML

= 0.2.5 =
* Minor bug fixes

= 0.2.4 =
* Display timezones for voting start/end hours

= 0.2.1 =
* Improved privacy policy and terms of use modals
* Redirect user to panel after voting

= 0.2.0 =
* Fixed many bugs and vulnerabilities
* BREAKING: "Create voting" page is now defined by a page template, not via plugin settings
* BREAKING: Other plugin options were renamed; re-configure the plugin after updating

= 0.1.0 =
* Initial public release of the plugin

== Links ==

* [Official site](https://concordamos.com.br/)
* [Source code](https://github.com/itsriodejaneiro/concordamos-plugin/)
* [User manual](https://github.com/itsriodejaneiro/concordamos-plugin/blob/main/README.md) (in Portuguese)
