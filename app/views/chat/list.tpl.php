
<div class="list-container list-container--conversations">

    <div class="contact-list">
        <h3 class="contact-title">Mes conversations </h3>
        <?php use App\Models\Messages;
        use App\Models\Users;
        if(!$activeChats) : ?>
            <div class="empty-display">Aucune conversation en cours</div>
        <?php endif;
        
        foreach($activeChats as $chat) : ?>

            <div class="contact-card" id="<?= $chat['chat_id']?>">
                <div class="contact-card__picture">
                    <img src="<?= $baseUri . '/assets/images/' . $chat['picture']?>" alt=""> 
                </div>
                <div class="contact-card__body">
                    <p>
                        <a class="tooltip" href="<?= $router->generate('contact-profile', ['id'=>$chat['id']])?>"><i class="fas fa-user"></i><span class="tooltip-inner">Voir le profil</span> </a>
                        <a class="tooltip" href="<?= $router->generate('chat-getChat', ['id'=>$chat['chat_id']])?>"><?= $chat['login'] ?><span class="tooltip-inner">Voir le chat</span> </a>
                    </p> 
                </div>
                <div class="contact-card__lastMessage">
                    <div class="bubble contact-card__lastMessage--body">
                        
                        <div class="contact-card__lastMessage--text"><?= html_entity_decode($chat['message']->getMessage()) ?></div>  
                    </div>
                    <p class="contact-card__lastMessage--aside"><span class="contact-card__lastMessage--author"> <?= Users::find($chat['message']->getAuthorId())->getLogin()?></span>
                    <span class="contact-card__lastMessage--date">
                    <?= date_format(new Datetime($chat['message']->getCreatedAt()), 'd/m') ?></span></p>
                    <a href="<?= $router->generate('chat-delete', ['id'=>$chat['chat_id']])?>?token=<?= $token ?>" class="tooltip" ><span class="tooltip-inner">Supprimer la conversation</span><i class="fas fa-trash"></i></a>
                   
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

