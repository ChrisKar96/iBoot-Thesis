<?php

if (isset($apiSpecPath)):?>
<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>iBoot - <?= lang('Text.api_spec'); ?></title>
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/swagger/swagger-ui.css') ?>">
  <link rel="icon" type="image/png" href="<?= base_url('assets/swagger/favicon-32x32.png') ?>" sizes=" 32x32" />
  <link rel="icon" type="image/png" href="<?= base_url('assets/swagger/favicon-16x16.png') ?>" sizes=" 16x16" />
  <style>
    html {
      box-sizing: border-box;
      overflow: -moz-scrollbars-vertical;
      overflow-y: scroll;
    }

    *,
    *:before,
    *:after {
      box-sizing: inherit;
    }

    body {
      margin: 0;
      background: #fafafa;
    }
  </style>
</head>

<body>
  <div id="swagger-ui"></div>

  <script src="<?= base_url('assets/swagger/swagger-ui-bundle.js') ?>"> </script>
  <script src="<?= base_url('assets/swagger/swagger-ui-standalone-preset.js') ?>"> </script>
  <script>
    window.onload = function() {
      // Begin Swagger UI call region
      window.ui = SwaggerUIBundle({
          spec: <?= file_get_contents($apiSpecPath); ?>,
          dom_id: '#swagger-ui',
          deepLinking: true,
          presets: [
              SwaggerUIBundle.presets.apis,
              SwaggerUIStandalonePreset
          ]
          <?php if (session()->has('user')): ?>
          ,onComplete: function() {
              // Default API key
              ui.preauthorizeApiKey("bearerAuth", "<?= session()->get('user')->token; ?>")
          }
          <?php endif; ?>
      })
      // End Swagger UI call region
    }
  </script>
</body>

</html>

<?php
endif; ?>

