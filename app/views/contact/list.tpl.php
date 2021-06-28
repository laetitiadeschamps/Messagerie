
<div class="list-container list-container--contacts">
  <div class="list-search">
      <div class="list-search__searchbar"><input type="text" placeholder="Saisissez un nom à chercher" id="searchText"><i class="fas fa-search"></i></div>
      <div class="list-search__users-list">
  </div>
</div>
<div class="contact-list">
    <h3 class="contact-title">Mes contacts </h3>
    <?php if(!$contacts) : ?>
        <div class="empty-display">Aucun contact pour le moment</div>
    <?php endif;
    foreach ($contacts as $contact) :?>
        <div class="contact-card">
           <div class="contact-card__picture"><img src="<?= $baseUri . '/assets/images/' . $contact->getPicture()?>" alt="contact-picure"></div> 
           <div class="contact-card__body">
               <p><a href="<?= $router->generate('contact-profile', ['id'=>$contact->getId()])?>"><span><?= $contact->getFirstname() ?> <?= $contact->getLastname() ?></span><span class="login-info <?= $contact->isConnected? 'login-info--online' : 'login-info--offline' ?>"></span></a>
               <div class="contact-card__actions">
                    <a class="tooltip" href="<?= $router->generate('chat-getChat', ['id'=>$contact->chat_id])?>"> <i class="fas fa-comments"></i><span class="tooltip-inner">Accéder au chat</span> </a><a class="tooltip" href="<?= $router->generate('contact-unfriend', ['id'=> $contact->getId()])?>?token=<?= $token ?>"><i class="fas fa-user-slash unfriend"></i><span class="tooltip-inner">Supprimer de la liste des amis</span></a></p>
           </div>
            </div>
        </div>
         
    <?php endforeach; ?>
</div>   

<template id="user-template">
        <div class="user-card">
           <div class="user-card__picture"><img src="" alt="contact-picture"></div> 
           <div style="display:none"></div>
           <div class="user-card__body">
               <p><a class="profile" href=""><span id="user-card__name"></span></a><a class="befriend"></p> 
            </div>
        </div>

     
    </template>
