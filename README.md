# Fixed-WHMCS-SpamExperts-Addon-2021-05
WHMCS is like your own personal hell some times, here I will try to share some small but life saving fixes

## Fixes

### Spam Experts addon v1.0

Original addon documentation and download URL: https://documentation.n-able.com/spamexperts/userguide/Content/Integration/whmcs-addon.htm?Highlight=whmcs

#### Spam Experts API call
All connections are now set to connect to https and corrected all "/" for api calls.

#### Spam Experts & Resellers Center
Spam Experts admin area addon page, will give a class error because it's conflicting with hooks loaded by Resellers Center. 
A non professional but working fix, is to not load the Resellers Center hooks based on specific URL. Change the [YOUR_WHMCS_URL] to your WHMCS URL.
