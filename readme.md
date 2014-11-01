magento-alternatehref
=====================

Tested on Magento 1.6.2.0

For multi-domain stores Google recomends adding <link rel="alternate" hreflang="en" href="<link>" /> for all language versions 
of a website.

For products and categories this can be done with http://inchoo.net/magento/implement-rel-alternate-links-in-magento/ but this does not work if combining speaking urls in cms/pages:

example.com/about-us
example.de/ueber-uns

This module adds an extra column to the database (called "alternate_href_common_identifier"), which is used to match different pages correctly.

All cms/pages that should be linked should get the same alternate_href_common_identifier added to the backend and then this module outputs the right link nodes.
