<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 22/06/2016
 * Time: 15:13
 */
namespace pseph\nff\formation\config;
class Piwik
{
    public static function getScript()
    {
        /** @noinspection Annotator */
        return "<!-- Piwik -->
<script>
  var _paq = _paq || [];
  _paq.push([\"setDomains\", [\"*.dart.lorol.co.uk\"]]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=\"//analytics.artonezero.com/piwik/\";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 3]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->
";
    }


    public static function getAlteriveScript()
    {
        return "<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-42973738-3\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-42973738-3');
</script>
";
    }


}

/// <noscript><p><img src="//analytics.artonezero.com/piwik/piwik.php?idsite=3" style="border:0;" alt="" /></p></noscript>
