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