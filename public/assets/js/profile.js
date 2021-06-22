const profile = {
    avatarIndex:0,
    init:function() {
        if(document.querySelector('#edit-profile')) {
                document.querySelector('#edit-profile').addEventListener('click', profile.handleUserEdit);
                profile.checkEditMode();
                profile.avatarIndex = 0;
                document.querySelector('.form__image--reset').addEventListener('click', profile.handleImageReset);
                document.querySelector('#photoUpload').addEventListener('change', profile.handlePhotoUpload);

        }
       
       //TODO display photo on upload
    },
    handlePhotoUpload:function(event) {
        const objectURL = URL.createObjectURL(event.target.files[0]);
       
        document.querySelector('.form__image img').src= objectURL;

    },
    checkEditMode:function() {
        let profileElement = document.querySelector('.form-container--profile');
        let src = document.querySelector('.form__image--picture').src;
        let srcArr = src.split('/');
        let fileName = srcArr[srcArr.length-1];
       
        if(profileElement.classList.contains('editMode')) {
           
            if(fileName.startsWith('avatar')) {
                
                document.querySelectorAll('.avatarSelect').forEach(arrow=> {
                    arrow.style.display='block';
                    arrow.addEventListener('click', profile.handleNextAvatar);
                }) } else {
                    document.querySelector('.fa-close').style.display="block";
                }
        } else {
          
                document.querySelectorAll('.avatarSelect').forEach(arrow=> {
                    arrow.style.display='none';
        
                }) 
                 document.querySelector('.fa-close').style.display="none";
               

        }



    }, 
 
    handleImageReset:function() {
        document.getElementById("photoUpload").value = "";
        let src = document.querySelector('.form__image--picture').src;
        let srcArr= src.split('/');
        let newSrc = srcArr[srcArr.length-1];
        document.querySelector('.form__image--picture').src = src.replace(newSrc, 'avatar-male-1.png');
        document.querySelector('#avatar').value='avatar-male-1.png';
        profile.checkEditMode();
    },
    handleNextAvatar:function(e) {
        let avatars =[
            'avatar-male-1.png',
            'avatar-female-1.png',
            'avatar-male-2.png',
            'avatar-female-2.png'
        ];
      
        if(e.currentTarget.id == 'prevAvatar') {
            profile.avatarIndex > 0 ? profile.avatarIndex-- : profile.avatarIndex= avatars.length-1;

        } else {
            profile.avatarIndex < avatars.length-1 ? profile.avatarIndex++ : profile.avatarIndex= 0;
        }

        console.log(avatars[profile.avatarIndex]);
        let src = document.querySelector('.form__image--picture').src;
        let srcArr= src.split('/');
        let newSrc = srcArr[srcArr.length-1];
    
        document.querySelector('.form__image--picture').src = src.replace(newSrc, avatars[profile.avatarIndex]);
        document.querySelector('#avatar').value=avatars[profile.avatarIndex];
        

    },
    handleUserEdit:function(e) {
      
        let profileElement = document.querySelector('.form-container--profile');
      profileElement.classList.toggle('editMode');
     profile.checkEditMode();
     e.preventDefault();
    //    if(profileElement.classList.contains('editMode')) {
    //     e.preventDefault();
    //    } 
    }
}