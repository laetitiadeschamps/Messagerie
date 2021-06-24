const chat = {
    editor:'',
    socket:'',
    init:function() {
        window.addEventListener('beforeunload', chat.disconnectUser);
        window.addEventListener('load', chat.connectUser);
        app.socket = new WebSocket('ws://localhost:8080');
        app.socket.onopen = function(e) {
           // 
        };
        
       if(document.querySelector('.editor')) {
        let currentChatId =  document.querySelector('#chatId').value;

        app.socket.onmessage = function(event) {
            const list = document.querySelector('.chat-messages');
            let data = JSON.parse(event.data);
            if(data.chat_id == currentChatId) {
                let templateElement = document.querySelector('#message-template');
            let messageFragment = templateElement
            .content
            .cloneNode(true)
            .querySelector('.chat-message');
            messageFragment.querySelector('.chat-message__author').textContent = data.author;
            let date = new Date(data.created_at);
            let month = date.getMonth()+1;
            let displayMonth = month >9 ? month : '0'+month;
            let displayDay = date.getDate() >9 ? date.getDate() : '0'+date.getDate();
            let displayHours = date.getHours() >9 ? date.getHours() : '0'+date.getHours();
            let displayMinutes = date.getMinutes() >9 ? date.getMinutes() : '0'+date.getMinutes();
            messageFragment.querySelector('.chat-message__time').textContent =  displayDay+'/'+displayMonth+' ' + displayHours+':'+ displayMinutes;
            messageFragment.querySelector('.chat-message__body').classList.add (data.author_id == document.querySelector('#userId').value ? 'isAuthor' : 'isNotAuthor');
            messageFragment.querySelector('.chat-message__body').innerHTML = data.message;
            
            list.prepend(messageFragment);

           

            
            let config = {
                method: 'GET',
                mode: "same-origin",
                credentials: "same-origin",
                cache: 'no-cache',
            }
           
            let request = fetch('http://0.0.0.0:8090/markAsRead/'+currentChatId, config);
            

        }
    }
        
        chat.handleMessageFormat();
        document.querySelector('#newPostForm').addEventListener('submit', chat.generateHTML);
      
        
       }
      
    //    if(document.querySelector(('.list-container--home'))) {
    //     app.socket = new WebSocket('ws://localhost:8080');
    //     app.socket.onmessage = function(event) {
    //         let notifications = parseInt(document.querySelector('.home-title .count').textContent);
    //         let newNotificationsCount = notifications+1;
    //         document.querySelector('.home-title .count').textContent = newNotificationsCount;
    //         let data = JSON.parse(event.data);
    //         if(document.querySelector('#'+ data.chat_id)) {
    //             // Si chat existe on incrémente le nombre de notifs


    //            let currentCount=  parseInt(document.querySelector('#'+ data.chat_id).querySelector('.count').textContent);
    //            let newCount = currentCount++;
    //            document.querySelector('#'+ data.chat_id).querySelector('.count').textContent = newCount;

    //         } else {
    //             // Sinon, on créé la card
    //             let templateElement = document.querySelector('#notifications-template');
    //             let notificationFragment = templateElement
    //             .content
    //             .cloneNode(true)
    //             .querySelector('.contact-card');
    //             notificationFragment.querySelector('.user-login') = data.author;
    //             notificationFragment.id = data.chat_id;
    //             notificationFragment.querySelector('a').href="/chat/" + data.chat_id;
    //             document.querySelector('.chat-list').prepend(notificationsFragment);
    //         }

    //     }
    //    }
    //    if(document.querySelector(('.list-container--conversations'))) {
    //     app.socket = new WebSocket('ws://localhost:8080');
    //     app.socket.onmessage = function(event) {
            
    //         let data = JSON.parse(event.data);
    //         if(document.querySelector('#'+ data.chat_id)) {
    //             // Si chat existe on modifie le dernier message + heure + auteur
    //             let date = new Date(data.created_at);
    //             let month = date.getMonth()+1;
    //             let displayMonth = month >9 ? month : '0'+month;
    //             let displayDay = date.getDate() >9 ? date.getDate() : '0'+date.getDate();


    //            document.querySelector('#'+ data.chat_id).querySelector('.contact-card__lastMessage--text').innerHTML = data.message;
    //            document.querySelector('#'+ data.chat_id).querySelector('.contact-card__lastMessage--author').innerHTML = data.author;
    //            document.querySelector('#'+ data.chat_id).querySelector('.contact-card__lastMessage--date').innerHTML = displayDay+'/'+displayMonth;

    //         } else {
    //             // Sinon, on créé la card
    //             let div = document.createElement('div');
    //             div.innerHTML = "De nouvelles conversations ont été créées, veuillez rafraichir la page"
    //             div.classList.add('empty-display');
    //             document.querySelector('.contact-list').prepend(notificationsFragment);
    //         }

    //     }
    //    }
       
       
    },
    connectUser:function() {
      
        const currentUserId = document.querySelector('#userId').value;
        let config = {
            method: 'GET',
            mode: "same-origin",
            credentials: "same-origin",
            cache: 'no-cache',
        }
       
        let request = fetch('http://0.0.0.0:8090/connectUser/'+currentUserId, config);
        

},
    disconnectUser:function() {
      
            const currentUserId = document.querySelector('#userId').value;
            let config = {
                method: 'GET',
                mode: "same-origin",
                credentials: "same-origin",
                cache: 'no-cache',
            }
           
            let request = fetch('http://0.0.0.0:8090/disconnectUser/'+currentUserId, config);
            
    
    },
    handleMessageFormat:function() {
        var toolbarOptions = [
            ['bold', 'italic'],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            [{ 'color': [] }, { 'background': [] }]
          ]
        app.editor = new Quill('.editor', {
            placeholder: 'Entrez votre message',
            modules: {
                toolbar: toolbarOptions,
                history: {
                    delay: 1000,
                    maxStack: 500,
                    userOnly: true
                  }
              },
            theme:'snow'
        });

    },
    
    generateHTML:function(e) {
        e.preventDefault();
        let chatId= document.querySelector('#chatId').value;
        document.querySelector('#newPostMessage').value = app.editor.root.innerHTML;
      
        let data = {
            chatId:chatId, 
            newPostMessage: document.querySelector('#newPostMessage').value
        }
        let myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
        let config = {
            method: 'POST',
            mode: "same-origin",
            credentials: "same-origin",
            cache: 'no-cache',
            headers:myHeaders,
            body : JSON.stringify(data)
        }
       
        let request = fetch('http://0.0.0.0:8090/chat/'+chatId, config);
        request.then(response=> {
            
            if (!response.ok) {
                throw Error(response.statusText);
            }
            return response.json();
        })

        .then(response=> {
            console.log(response);
            let data = {
                message:document.querySelector('#newPostMessage').value,
                chat_id:document.querySelector('#chatId').value,
                author:document.querySelector('#authorName').value,
                author_id:document.querySelector('#authorId').value,
                created_at:Date.now()
            }
           
            app.socket.send(JSON.stringify(data));

        })
        .catch(error=> {
            console.log('error');
        })
        //updating websocket
        
        //document.querySelector('#newPostForm').submit();

    }
    
}
