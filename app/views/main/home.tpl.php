<div class="list-container list-container--home">
    <h1 class="home-title">Mes notifications <span class="count"><?= $countUnread['quantity']?></span></h1>
    <div class="home-body">
        
        <div class="chat-list">
        <?php if(!$notifications) : ?>
            <div class="empty-display">Aucune nouvelle notification</div>
        <?php endif;
    
        use App\Models\Users;
            foreach($notifications as $chat) : ?>

                <div class="contact-card" id="<?php $chat['chat']->id?>">
                    <h4>Chat avec <?= Users::find($chat['user_id'])->login?><span class="count"><?= $chat['message_quantity']?></span> </h4>
                   
                    <p><a href="<?= $router->generate('chat-getChat', ['id'=>$chat['chat']->id])?>">Aller sur le chat</a></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<template id="notifications-template">
     
    <div class="contact-card" id="">
        <h4>Chat avec <span class="user-login"></span><span class="count">1</span> </h4>
                   
        <p><a href="">Aller sur le chat</a></p>
    </div>

  
 </template>