<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mon tchat</title>
    <link rel="stylesheet" href="<?= $baseUri?>/assets/css/reset.css">
<link rel="stylesheet" href="<?= $baseUri?>/assets/css/style.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
        <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
        <link rel="icon" href="<?= $baseUri . '/assets/images/logo-web.png'?>" />

</head>

<body>
<div class="container">
   <header> 
    <?php
    // On inclut des sous-vues => "partials"
    include __DIR__.'/../partials/nav.tpl.php'; ?>

   </header>
   <div class="flash-container">
   <?php include __DIR__.'/../partials/flash.tpl.php';
    ?>
</div>
  
