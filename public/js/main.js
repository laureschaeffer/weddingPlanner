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


//----------------------------------scroll-up
const scrollUp = () =>{
    const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, show the show-croll element
    this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
                        : scrollUp.classList.remove('show-scroll')

}
window.addEventListener('scroll', scrollUp)

//----------------------------------fonction pour afficher ou enlever le formulaire modifie les infos profil
openEditForm = document.querySelector('#openFormProfil');
formEdit = document.querySelector('.editProfil');

openEditForm.addEventListener('click', () => {
    formEdit.classList.toggle('open')
})