# Language router

**THIS EXTENSION IS NOT MAINTAINED ANYMORE!** We do not provide support for this code anymore. If you want to take over this project, feel free to fork or re-upload it under your own namespace.

[![Build Status](https://travis-ci.org/nimius/language-router.svg?branch=master)](https://travis-ci.org/nimius/language-router)

A simple extension providing redirects to languages or pages, both for single- and multi-tree setups, based on
user agent language and/or geoip.

**TypoScript Example**:

    plugin.tx_languagerouter.settings {
        redirectCookie {
            // Redirect regardless if a redirect has already been made (which sets a cookie).
            // This is especially useful if you have a visitable landing page that not just redirects.
            disregard = 1

            // Alternatively, setting disregard = 0 (default) but an expiration date, the
            // cookie will expire after the given amount of seconds, leading to a redirect
            // each time a cookie is either expired or not set.
            expirationInSeconds = 21600
        }

        routes {
            // First priority: Route by country, detected from geoIP.
            1 {
                // This will return a 2-char country code.
                detection = country
                targets {
                    // Redirect to the current page with L = 1.
                    DE.L = 1

                    // Redirect to our special page (id = 13) in default language (L = 0) to choose country language.
                    CH {
                        L = 0
                        id = 3
                    }
                }
            }

            // Second priority: Route by browser language
            2 {
                // This will return a locale.
                detection = acceptedLanguages

                settings {
                    qualityTreshold = 0.6
                }

                targets {
                    // Redirect to our special page (id = 4) in German (L = 1).
                    de_CH {
                        L = 1
                        id = 4
                    }

                    // Redirect to our special page (id = 4) in French (L = 2).
                    fr_CH {
                        L = 2
                        id = 4
                    }

                    // Redirect to our special page (id = 4) in Italian (L = 3).
                    it_CH {
                        L = 3
                        id = 4
                    }

                    // Fallback and general German language: Redirect to the current page with L = 1.
                    de_DE.L = 1
                }
            }

            // Third priority: If 1. and 2. don't match anything.
            3 {
                detection = fallback

                // Attention: "No detection" can't have multiple targets, that' why it's "target".
                target {
                    L = 23
                }
            }
        }
    }


## Usage

Language router can be used in two ways:

 1. Include the provided plugin element on a landing page that is intended to redirect your visitors to the correct starting point.
 2. Add the plugin via TypoScript

Be aware that the plugin is of type `USER_INT` which leads to the page(s) it is used on being uncached.


## Detection types.

Language router provides the following detection types:


### Detection: acceptedLanguages

The acceptedLanguage detection mode reads the `HTTP_ACCEPT_LANGUAGE` parameter and parses all accepted languages. This parameter usually contains
one locale (like `de_CH`) and a few language codes that are also supported (like `de`, `en`, `fr`). Language codes are also converted to locales
(as in `de_DE` for `de`, `en_EN` for `en` and `fr_FR` for `fr`) to have a consistent configuration.

**Explanative example**  
If your operating system has set English as its preferred language, and German as it's second one, your browser may send an `HTTP_ACCEPT_LANGUAGE` header that looks like this:

    en-US,en;q=0.8,de;q=0.6,fr;q=0.4

This will then process `en_US`, `en_EN`, `de_DE`, `fr_FR`, in this order. You can skip languages below a certain quality level by setting `settings.qualityTreshold`.

Before any redirect, the current page uid and language parameter is compared to the configured one, in order to not end up in a redirect loop.


### Detection: country

The country detection relies on php-geoip being available. If it is not available, the country detection configuration is being ignored. To get information about whether you can or cannot use this detection type, a status report is provided, which you'll find in the *System > Reports* backend module.

In order to compare the current page's country with the configured one, you must set *config.country* for any language condition block,
otherwise language router will not know to which country the currently rendered page belongs to, and ends up in a redirect loop.

Example:

    [globalVar = GP:L=1]
        config {
            sys_language_uid = 1
            language = de
            locale_all = de_CH
            country = CH
    		htmlTag_setParams = lang="de" class="lang-de"
        }
    [end]


### Detection: fallback

As its name says, it's not a real detection, but a fallback. Use this type of configuration if you need to redirect to a fallback page in case any previous route does not match.


## Examples

### Countries with both one or more languages

Assuming the following language configuration:

| Country     | Language | Locale | `sys_language_uid` |
| ----------- | -------- | ------ | ------------------ |
| Switzerland | German   | de_CH  | 0                  |
| Switzerland | French   | fr_CH  | 1                  |
| Germany     | German   | de_DE  | 2                  |
| Belgium     | Dutch    | nl_BE  | 3                  |
| Belgium     | French   | fr_BE  | 4                  |
| (Any)       | English  | en_EN  | 5                  |

The routes to match the setup from above may look like this:

    plugin.tx_languagerouter.settings {
        routes {
          /*
           * Route by browser language first in order to
           * handle countries with multiple languages first.
           *
           * This only works if the operating system of a visitor
           * has set the correct locale for the country the
           * visitor is living in!
           */
          1 {
            detection = acceptedLanguages
            targets {
              fr_CH.L = 1
              fr_BE.L = 4
            }
          }

          /*
           * Any unmatched routes from the previous
           * by-browser-language configuration will be
           * routed by the country they're living in.
           */
          2 {
            detection = country
            targets {
              CH.L = 0
              DE.L = 2
              BE.L = 3
            }
          }

          /*
           * If neither the first nor the second rule matched,
           * a fallback to our logical "default" language is made.
           */
          3 {
            detection = fallback
            target {
              L = 5
            }
          }
        }
      }


## Common errors

Here's a list of common errors experienced when implementing. Please make sure to double-check these before
filing a bug report.


### .htaccess redirects

Using rewrite rules to ensure that an URL contains a language part may break language routing due to the
rewrite redirecting away from the plugin before it can be executed.

Example:

```
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} !^/en/(.*)$
RewriteCond %{REQUEST_URI} !^/de/(.*)$
RewriteCond %{REQUEST_URI} !^/fr/(.*)$
RewriteRule ^(.*)$ /en/$1 [L,R=301]
```

If your .htaccess (or NGINX configuration) contains redirects as displayed in the example above, remove them
and properly configure language routing by e.g. using the `fallback` detection.
