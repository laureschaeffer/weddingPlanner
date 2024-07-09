//----------------------------------responsive menu burger
const navIcon = document.querySelector('#nav-icon');

navIcon.addEventListener('click', toggleNav)

function toggleNav(){
    console.log("test")
    // if ( .className === "nav"){
    //      .className = "navbarResponsive";
    // } else{
    //      .className = "nav" ;
    // }
}


//----------------------------------fonction pour afficher ou enlever le formulaire page showProject.php

// a chaque bouton, ajoute un ecouteur d'evenement
    buttons = document.querySelectorAll('.change-form');
    buttons.forEach(button => {
        button.addEventListener('click', toggleForm);
    });

    // Fonction pour afficher/masquer le formulaire correspondant à l'aide de l'id passé dans le data-attribute
    function toggleForm(event) {
        const targetId = event.currentTarget.getAttribute('data-target');
        console.log(targetId)

        const form = document.getElementById(targetId); //recupere le formulaire qui a l'id du boutton qu'on a récupéré

        //switch de display
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }