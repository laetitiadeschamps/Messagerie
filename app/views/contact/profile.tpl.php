
<div class="list-container list-container--user-profile user-profile">
  <header class="user-profile__picture">
      <img src="<?= $baseUri . '/assets/images/' . $user->picture?>" alt="">
  </header>
  <div class="user-profile__infos">
      <h3 class="user-infos__title"><?= $user->login ?></h3>
      <aside class="user-infos__aside">
      <small><?= $isFriend ? 'Vous êtes amis avec cette personne':''?></small>
          <?php if($user->id == $_SESSION['id']) : ?>
            <span>(Vous)</span>
          <?php elseif($isFriend) : ?>
            <a class="tooltip" href="<?= $router->generate('contact-unfriend', ['id'=>$user->id]) ?>?token=<?=$token ?>"> <i class="fas fa-user-minus"></i><span class="tooltip-inner">Supprimer de la liste d'amis</span></a>
          <?php else : ?>
            <a class="tooltip" href="<?= $router->generate('contact-befriend', ['id'=>$user->id]) ?>?token=<?=$token ?>"> <i class="fas fa-user-plus"></i><span class="tooltip-inner">Ajouter à la liste d'amis</span></a>
          <?php endif;?>
           
      </aside>
      <div class="user-infos__body">
      <h3>Informations </h3>
          <p>Date de naissance : <?= $user->birthdate ?></p>
          <p>Description : <span class="user-description"> <?= $user->description ?></span></p>
      </div>
      <?php if($isFriend) : ?>
      <div class="user-infos__chat">
        <a href="<?= $router->generate('chat-getChat', ['id'=>$chat->id]) ?>?token=<?=$token ?>">Accéder au chat</a>
      </div>
      <?php endif; ?>
  </div>
 
</div>
