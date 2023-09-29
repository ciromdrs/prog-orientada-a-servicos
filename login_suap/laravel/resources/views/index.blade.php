<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cliente SUAP Javascript</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
  </head>
  <body>
		<section class="hero is-link is-bold">
	    <div class="hero-body">
	      <div class="container">
	        <h1 class="title">Cliente SUAP Javascript</h1>
	      </div>
	    </div>
	  </section>
  	<section class="section">
		  <div class="container">
	  		<div class="is-anonymous content">
					<h2>Você não está autenticado</h2>
					<a class="button is-success is-large" id="suap-login-button">Login com SUAP</a>
		  	</div>
		  </div>
	  </section>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="js/js.cookie.js"></script>
  		<script src="js/client.js"></script>
		<script src="js/settings.js"></script>
    <script>
      var suap = new SuapClient(
		SUAP_URL, CLIENT_ID, HOME_URI, REDIRECT_URI, SCOPE
	  );
      suap.init();
      $(document).ready(function () {
          $("#suap-login-button").attr('href', suap.getLoginURL());
      });
    </script>
  </body>
</html>
