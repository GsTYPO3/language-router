# Language router

A simple extension providing redirects to languages or pages, both for single- and multi-tree setups, based on
user agent language and/or geoip.

**TypoScript Example**:

    plugin.tx_languagerouter.settings {
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
        }
    }


## Detection types.

Language router provides the following detection types:


### Detection: acceptedLanguages

The acceptedLanguage detection mode reads the *HTTP_ACCEPT_LANGUAGE* parameter and parses all accepted languages. This parameter usually contains
one locale (like *de_CH*) and a few language codes that are also supported (like *de*, *en*, *fr*). Language codes are also converted to locales
(as in *de_DE* for *de*, *en_EN* for *en* and *fr_FR* for *fr*) to have a consistent configuration.

Before any redirect, the current page uid and language parameter is compared to the configured one, in order to not end up in a redirect loop.


### Detection: country

The country detection relies on php-geoip being available. If it is not available, the country detection configuration is being ignored.

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
