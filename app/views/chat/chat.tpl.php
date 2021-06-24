<div class="list-container list-container--chat">
 
    <input type="hidden" id="userId" name="userId" value=<?= $_SESSION['id']?>>
    <h3 class="chat-title"><img src="<?= $baseUri . '/assets/images/' . $contact->getPicture()?>" alt=""> <img src="<?= $baseUri . '/assets/images/' . $_SESSION['userObject']->getPicture()?>" alt=""> <span class="chat-title__text">Conversation avec <?= $contact->login ?> </span></h3>
    
    <div class="chat-new-message">
        <h3 class="new-message__title">Nouveau message</h3>
        <form id="newPostForm" class="new-message__form" action="" method="POST">
            <div name="newPost" class="editor"></div>
            <div id="toolbar"></div>
            <input type="hidden" name="newPostMessage" id="newPostMessage" value=""/>
            <input type="hidden" name="chatId" id="chatId" value="<?=$chat->id ?>"/>
            <input type="hidden" name="authorId" id="authorId" value="<?=$_SESSION['id'] ?>"/>
            <input type="hidden" name="authorName" id="authorName" value="<?=$_SESSION['userObject']->login ?>"/>
            <input type="submit" class="btn submit">
        </form>
    </div>
    <div class="chat-messages">
        <?php if(!$messages) : ?>
          <div class="empty-display">Aucun message avec cette personne pour le moment</div>
          <?php endif; ?>
        <?php foreach($messages as $message) : ?>
          <div class="chat-message">
              <span class="chat-message__infos">
                  <span class="chat-message__author"><?= $message->author_name ?></span>
                  <span class="chat-message__time"><?= date('d/m H:i', strtotime($message->created_at)) ?></span> 
              </span>
              <span class="bubble chat-message__body <?= $message->getAuthorId() == $_SESSION['id'] ? 'isAuthor' : 'isNotAuthor'?>" style="white-space:pre-wrap"><?= html_entity_decode($message->getMessage())  ?></span>
          </div>
        <?php endforeach; ?>
    </div>
 
</div>

<template id="message-template">

    <div class="chat-message">
        <span class="chat-message__infos">
            <span class="chat-message__author"></span> 
            <span class="chat-message__time"></span> 
        </span>
        <span class="bubble chat-message__body"></span>
    </div>
</template>

