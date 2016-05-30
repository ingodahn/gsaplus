<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>GSA Online Plus - @yield('title')</title>

<script src="/js/jquery.min.js" charset="utf-8"></script>
<script src="/js/bootstrap.min.js" charset="utf-8"></script>
<script src="/js/parsley.min.js" charset="utf-8"></script>
<script src="/js/i18n/parsley-de.js" charset="utf-8"></script>
<script src="/js/parallax.min.js" charset="utf-8"></script>
<script src="/js/enables.js" charset="utf-8"></script>
<script src="/js/moment.min.js" charset="utf-8"></script>
<script src="/js/i18n/moment-de.js" charset="utf-8"></script>
<script src="/js/bootstrap-datetimepicker.min.js" charset="utf-8"></script>
<script src="/js/sweetalert.min.js"></script>
<script src="/js/jquery.textarea_autosize.min.js"></script>
<link rel="stylesheet" href="/css/app.css" media="screen" charset="utf-8">

<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  @if($isLoggedIn && !$piwikOptOut)
    _paq.push(['setUserId', '{{$Name}}']);
  @endif
  (function() {
    var u="//{{config('piwik.host')}}/{{config('piwik.path')}}";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', {{config('piwik.site_id')}}]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//{{config('piwik.host')}}{{config('piwik.path')}}/piwik.php?idsite={{config('piwik.site_id')}}" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
