//----------------------------------scroll-up
const scrollUp = () =>{
    const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, show the show-croll element
    this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
                        : scrollUp.classList.remove('show-scroll')

}
window.addEventListener('scroll', scrollUp)

//----------------------------------page profil: navbar----------------------------------

  //les elements de la nav
  const reservationBtn = document.querySelector('.open-reservation') 
  const projetBtn = document.querySelector('.open-projet') 
  const rdvBtn = document.querySelector('.open-rendezvous') 
  const paramBtn = document.querySelector('.open-parametre') 
  
  //si on trouve cet élément donc que nous sommes sur la page profil
  if(reservationBtn){
    //les sections
    const reservationEl = document.querySelector('#profil-reservation')
    const projetEl = document.querySelector('#profil-projet')
    const rdvEl = document.querySelector('#profil-rdv')
    const paramEl = document.querySelector('#profil-parametre')
    
    // par defaut 
    projetEl.classList.add('active')
    projetBtn.classList.add('nav-active')
  
    //fonction montre la div
    function showElement(element, navElement){
      // enleve la classe active à tous les elements et son "btn" de la nav
      reservationEl.classList.remove('active')
      projetEl.classList.remove('active')
      rdvEl.classList.remove('active')
      paramEl.classList.remove('active')
  
      reservationBtn.classList.remove('nav-active')
      projetBtn.classList.remove('nav-active')
      rdvBtn.classList.remove('nav-active')
      paramBtn.classList.remove('nav-active')
    
      element.classList.add('active')
      navElement.classList.add('nav-active')
    }
    
    // ecouteurs d'evenement 
    reservationBtn.onclick = () => {
      showElement(reservationEl, reservationBtn)
    }
  
    projetBtn.onclick = () => {
      showElement(projetEl, projetBtn)
    }
  
    rdvBtn.onclick = () => {
      showElement(rdvEl, rdvBtn)
    }

    paramBtn.onclick = () => {
      showElement(paramEl, paramBtn)
    }

  }