# Introduction
This extension will provide a tracking code to recognize anonymous B2B website visitors with Leadinfo. The code will be placed in front of the </head> tag to each page rendered by TYPO3.

# Installing and configure the extension
Install the Extensions as usual. Once you have installed, you will see an extra item in your in Site Management menu. Here you need to fill in your Leadinfo Site ID, you can find and copy this ID within Leadinfo.

Once you have entered the Leadinfo Site ID the Leadinfo tracking code is automatically added on your website. Click on ‘Verify Traffic’ within Leadinfo to check if the code is installed successfully.

Note: the tracking code is rendered together with the page and stored in the page
cache. If you edit `config/sites/<identifier>/config.yaml` directly instead of
using the backend, flush the TYPO3 caches so the change takes effect.

# Links
- Contact: https://leadinfo.com/en/contact
- Help Centre: https://help.leadinfo.com

# Content Security Policy
The tracking snippet dynamically inserts a `<script>` element that loads
`https://cdn.leadinfo.net/ping.js`. If you run a Content Security Policy, the
extension ships the required rules itself in
`Configuration/ContentSecurityPolicies.php`: it extends `script-src` with
`cdn.leadinfo.net` and `connect-src` with `*.leadinfo.net` for the frontend
scope. The inline snippet is registered with the `csp` option, so TYPO3 adds a
nonce — or a SHA-256 hash, if the site enables `useHash` in its `csp.yaml`.

Only add these sources manually if your site overrides the policy in a way that
drops the extension's mutations.
