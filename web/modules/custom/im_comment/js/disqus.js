function load_disqus( disqus_shortname ) {
  var disqus_trigger = document.getElementById('disqus_trigger'),
      disqus_target = document.getElementById('disqus_thread'),
      disqus_embed  = document.createElement('script'),
      disqus_hook   = (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]);

  // Load script asynchronously only when the trigger and target exist
  if( disqus_target && disqus_trigger ) {
    disqus_embed.type = 'text/javascript';
    disqus_embed.async = true;
    disqus_embed.src = '//' + disqus_shortname + '.disqus.com/embed.js';
    disqus_hook.appendChild(disqus_embed);
    disqus_trigger.remove();
  }
}
