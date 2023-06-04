import{_ as n,r as s,o,c as r,a as e,b as t,d as l,e as i,f as d}from"./app-c303192f.js";const h={},c=e("h2",{id:"database-connection",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#database-connection","aria-hidden":"true"},"#"),t(" Database Connection")],-1),f=e("u",null,"string",-1),g={href:"https://laravel.com/docs/10.x/database#configuration",target:"_blank",rel:"noopener noreferrer"},u=d('<table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;"><code>I18N_DB_CONNECTION</code></td><td style="text-align:left;"><code>i18n.database.connection</code></td><td style="text-align:left;"><code>&quot;mysql&quot;</code></td></tr></tbody></table><h2 id="language-table-name" tabindex="-1"><a class="header-anchor" href="#language-table-name" aria-hidden="true">#</a> Language Table Name</h2><p>A <u>string</u> that determines the table name used to store language records.</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;"><code>I18N_DB_LANGUAGE_TABLE</code></td><td style="text-align:left;"><code>i18n.database.tables.languages</code></td><td style="text-align:left;"><code>&quot;languages&quot;</code></td></tr></tbody></table><h2 id="language-model" tabindex="-1"><a class="header-anchor" href="#language-model" aria-hidden="true">#</a> Language Model</h2><p>A <u>string</u> that determines the class name used for the language model.</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.database.models.language</code></td><td style="text-align:left;"><code>\\Sirthxalot\\Laravel\\I18n\\Models\\Language::class</code></td></tr></tbody></table><h2 id="translation-table-name" tabindex="-1"><a class="header-anchor" href="#translation-table-name" aria-hidden="true">#</a> Translation Table Name</h2><p>A <u>string</u> that determines the table name used to store translation records.</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;"><code>I18N_DB_TRANSLATION_TABLE</code></td><td style="text-align:left;"><code>i18n.database.tables.translations</code></td><td style="text-align:left;"><code>&quot;translations&quot;</code></td></tr></tbody></table><h2 id="translation-model" tabindex="-1"><a class="header-anchor" href="#translation-model" aria-hidden="true">#</a> Translation Model</h2><p>A <u>string</u> that determines the class name used for the translation model.</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.database.models.translation</code></td><td style="text-align:left;"><code>\\Sirthxalot\\Laravel\\I18n\\Models\\Translations::class</code></td></tr></tbody></table><h2 id="i18n-driver" tabindex="-1"><a class="header-anchor" href="#i18n-driver" aria-hidden="true">#</a> I18n Driver</h2><p>A <u>string</u> that determines the driver used for the I18n service.</p><p><strong>Supported Drivers</strong>: &quot;file&quot; or &quot;database&quot;</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;"><code>I18N_DRIVER</code></td><td style="text-align:left;"><code>i18n.driver</code></td><td style="text-align:left;"><code>&quot;database&quot;</code></td></tr></tbody></table><h2 id="locale-session-key" tabindex="-1"><a class="header-anchor" href="#locale-session-key" aria-hidden="true">#</a> Locale Session Key</h2><p>A <u>string</u> that determines the session key that could be used in order to load the locale for a user.</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.locale_sk</code></td><td style="text-align:left;"><code>&quot;i18n_locale&quot;</code></td></tr></tbody></table><h2 id="translation-scan-paths" tabindex="-1"><a class="header-anchor" href="#translation-scan-paths" aria-hidden="true">#</a> Translation Scan Paths</h2><p>An <u>array</u> that lists each directory path were the I18n service should scan for missing translations.</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.scan_paths</code></td><td style="text-align:left;"><code>[app_path(), base_path(&#39;routes&#39;), resource_path()]</code></td></tr></tbody></table><h2 id="translation-methods" tabindex="-1"><a class="header-anchor" href="#translation-methods" aria-hidden="true">#</a> Translation Methods</h2><p>An <u>array</u> that lists each translation method used within the I18n service to scan for missing translations.</p><table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.translation_methods</code></td><td style="text-align:left;"><code>[&#39;__&#39;, &#39;trans_choice&#39;]</code></td></tr></tbody></table><h2 id="language-add-form-request" tabindex="-1"><a class="header-anchor" href="#language-add-form-request" aria-hidden="true">#</a> Language Add Form Request</h2>',27),y={href:"https://laravel.com/docs/10.x/validation#form-request-validation",target:"_blank",rel:"noopener noreferrer"},x=d('<table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.validation.language.add</code></td><td style="text-align:left;"><code>Sirthxalot\\Laravel\\I18n\\Http\\Requests\\AddLanguageRequest::class</code></td></tr></tbody></table><h2 id="translation-add-form-request" tabindex="-1"><a class="header-anchor" href="#translation-add-form-request" aria-hidden="true">#</a> Translation Add Form Request</h2>',2),b={href:"https://laravel.com/docs/10.x/validation#form-request-validation",target:"_blank",rel:"noopener noreferrer"},m=d('<table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.validation.translation.add</code></td><td style="text-align:left;"><code>Sirthxalot\\Laravel\\I18n\\Http\\Requests\\AddTranslationRequest::class</code></td></tr></tbody></table><h2 id="translation-update-form-request" tabindex="-1"><a class="header-anchor" href="#translation-update-form-request" aria-hidden="true">#</a> Translation Update Form Request</h2>',2),v={href:"https://laravel.com/docs/10.x/validation#form-request-validation",target:"_blank",rel:"noopener noreferrer"},_=d('<table><thead><tr><th style="text-align:left;">.env</th><th style="text-align:left;">config key</th><th style="text-align:left;">default value</th></tr></thead><tbody><tr><td style="text-align:left;">-</td><td style="text-align:left;"><code>i18n.validation.translation.update</code></td><td style="text-align:left;"><code>Sirthxalot\\Laravel\\I18n\\Http\\Requests\\UpdateTranslationRequest::class</code></td></tr></tbody></table>',1);function p(q,k){const a=s("ExternalLinkIcon");return o(),r("div",null,[c,e("p",null,[t("A "),f,t(" that determines the "),e("a",g,[t("database connection"),l(a)]),t(" used for I18n models, migrations etc.")]),u,e("p",null,[t("A "),e("a",y,[t("form request"),l(a)]),t(" class that determines the validation used to validate data before adding a language.")]),x,e("p",null,[t("A "),e("a",b,[t("form request"),l(a)]),t(" class that determines the validation used to validate data before adding a translation.")]),m,e("p",null,[t("A "),e("a",v,[t("form request"),l(a)]),t(" class that determines the validation used to validate data before updating a translation.")]),_,i("                            that's all folks!                            ")])}const I=n(h,[["render",p],["__file","index.html.vue"]]);export{I as default};