//----------------------------------scroll-up
const scrollUp = () =>{
    const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, show the show-croll element
    this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
                        : scrollUp.classList.remove('show-scroll')

}
window.addEventListener('scroll', scrollUp)

//----------------------------------page profil: navbar----------------------------------

  // les elements de la nav
const navButtons = {
  reservation: document.querySelector('.open-reservation'),
  projet: document.querySelector('.open-projet'),
  rdv: document.querySelector('.open-rendezvous'),
  param: document.querySelector('.open-parametre')
};

// si on trouve cet élément, donc nous sommes sur la page profil
if (navButtons.projet) {
  // les sections correspondantes
  const sections = {
    reservation: document.querySelector('#profil-reservation'),
    projet: document.querySelector('#profil-projet'),
    rdv: document.querySelector('#profil-rdv'),
    param: document.querySelector('#profil-parametre')
  };

  // par défaut affiche la premiere section projet
  sections.projet.classList.add('active');
  navButtons.projet.classList.add('nav-active');

  // affiche une section et activer son bouton
  function showElement(selectedSection, selectedButton) {
    // enlever la classe 'active' des sections et des boutons
    Object.values(sections).forEach(section => section.classList.remove('active'));
    Object.values(navButtons).forEach(button => button.classList.remove('nav-active'));

    // ajouter la classe 'active' à la section et au bouton sélectionnés
    selectedSection.classList.add('active');
    selectedButton.classList.add('nav-active');
  }

  // ajout écouteur d'événements
  Object.keys(navButtons).forEach(key => {
    navButtons[key].onclick = () => showElement(sections[key], navButtons[key]);
  });
}
