//FOR CHECKMRK
document.querySelector(".checkbox").addEventListener("click", function(){
    document.querySelector(".mycheckbox").classList.toggle("mycheckbox-clicked");
})

//ICON EFFECTS
const icons = document.querySelectorAll(".icon");

for (let icon of icons) {
    icon.addEventListener("mouseover", function(){
        icon.querySelector("i").style.color = "white";
        icon.querySelector("i").style.opacity = 1;
    })

    function changeIconColor(){
        icon.querySelector("i").style.color = "#" + 131821;
        icon.querySelector("i").style.opacity = 0.3;
    }

    icon.addEventListener("mouseout", changeIconColor)

    icon.addEventListener("click", function(e){
        if(e.target.className == "fab fa-facebook-f"){
            icon.style.backgroundColor =  "#2F4A80";
            icon.removeEventListener("mouseout", changeIconColor);
        }

        if(e.target.className == "fab fa-instagram"){
            icon.style.backgroundColor = "#8F2762";
            icon.removeEventListener("mouseout", changeIconColor);
        }

        if(e.target.className == "fab fa-twitter"){
            icon.style.backgroundColor =  "#177FBF";
            icon.removeEventListener("mouseout", changeIconColor);
        }

        if(e.target.className == "fab fa-youtube"){
            icon.style.backgroundColor =  "#CC0000";
            icon.removeEventListener("mouseout", changeIconColor);
        }
    })
}

//CHANGE COLOR OF INPUT ARROW

document.querySelector(".text-input-wrapper").addEventListener("mouseover", function(){
    document.querySelector(".arrow").setAttribute("src", "./img/ic_arrow (1).jpg");
})

document.querySelector(".text-input-wrapper").addEventListener("mouseout", function(){
    document.querySelector(".arrow").setAttribute("src", "./img/ic_arrow.jpg");
})


//FORM VALIDATION

function validate(e){
    let email = document.querySelector(".text-input").value;
    const message = document.querySelector(".message");
    const messageEmpty = document.querySelector(".message-empty");
    const messageColombia = document.querySelector(".message-colombia");
    const checkboxMessage = document.querySelector(".checkboxErr");
    
    function styleErrorMsg(){
        message.style.display = "block";
    }

    re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   
    if(re.test(email) == false && email != ""){
        message.innerText = "Please provide a valid e-mail address";
        styleErrorMsg();
        e.preventDefault();
    }else{
        message.style.display = "none";
    }

    if (email == ""){
        messageEmpty.innerText = "Email address is required";
        messageEmpty.style.display = "block";
        e.preventDefault();
    }else{
        messageEmpty.style.display = "none";
    }

    if (document.querySelector(".checkbox").checked == false){
        checkboxMessage.innerText = "You must accept the terms and conditions";
        checkboxMessage.style.display = "block";
        e.preventDefault();
    }else{
        checkboxMessage.style.display = "none";
    }

    let emailEnding = email.substr(email.length - 3);

    if (emailEnding == ".co"){
        messageColombia.innerText = "We are not accepting subscriptions from Colombia emails";
        messageColombia.style.display = "block";
        e.preventDefault();
    }else{
        messageColombia.style.display = "none";
    }

    return true;
}



