const app = {
  
    init:function() {
       if(document.querySelector('.dropdown')) {
        document.querySelectorAll('.dropdown').forEach(dropdown=> {
            dropdown.addEventListener('click', app.handleDropdownClick);
        }
        )
       }
       document.querySelector('.hamburger-icon').addEventListener('click', app.handleHamburgerClick);
        login.init();
       profile.init();
       contact.init();
       chat.init();
    },
    handleDropdownClick:function(e) {
        e.currentTarget.classList.toggle('open');
    },
    handleHamburgerClick:function(e) {
        console.log('click');
        document.querySelector('#hamburger-nav').classList.toggle('open');
    }
   
    
}

document.addEventListener('DOMContentLoaded', app.init);