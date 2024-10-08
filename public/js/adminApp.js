//----------------------------------diagramme chartjs

//constantes des éléments
const canvasEP = document.getElementById('diag-etat-projet');
const canvasPM = document.getElementById('diag-projet-mois')
const canvasCA = document.getElementById('diag-chiffre-affaire')

//si je récupère bien l'élément, donc que nous sommes sur la bonne page
if(canvasEP){
  //tableaux donnés dans les data-attribute
  let etatProjets = JSON.parse(canvasEP.dataset.etatProjet);
  let projetMois = JSON.parse(canvasPM.dataset.projetMois);
  let chiffreAffaires = JSON.parse(canvasCA.dataset.chiffreAffaire);
  
  //tableaux pour "projets par mois"
  let months = []
  let counts = []
  
  projetMois.forEach(element => {
      months.push(element.month); //month du tableau associatif
      counts.push(element.count); //count
    });
  
  
  //tableaux pour "chiffre d'affaire mensuel moyen"
  let caMonths = []
  let caAvg = []
  
  chiffreAffaires.forEach(element => {
      caMonths.push(element.month); //month du tableau associatif
      caAvg.push(element.chiffre_affaire); //count
    });
  
  // diagramme etats projet
  new Chart(canvasEP, {
    type: 'doughnut',
    data: {
      labels: ['en cours', 'en attente', 'accepté', 'refusé'],
      datasets: [{
        data: etatProjets,
        backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            '#711C80',
            '#6D8235',
            '#39F6B1'
          ],
          hoverOffset: 4
      }]
    }
    
  });
  
  // diagramme projets par mois
  new Chart(canvasPM, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
          label: "Nombre de projets",
          data: counts,
          backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 205, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(201, 203, 207, 0.2)'
            ],
          borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(255, 205, 86)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)',
              'rgb(201, 203, 207)'
            ],
          borderWidth: 1
      }]
    }
    
  });
  
  // diagramme chiffre affaire mensuel
  new Chart(canvasCA, {
    type: 'line',
    data: {
      labels: caMonths,
      datasets: [{
        label: 'Mensuel en euro',
        data: caAvg,
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
      }]
    }
    
  });

}


//----------------------------------fonction pour afficher ou enlever le formulaire page detail de projet

// a chaque bouton, ajoute un ecouteur d'evenement
buttons = document.querySelectorAll('.change-form');

if(buttons){

  buttons.forEach(button => {
      button.addEventListener('click', toggleForm);
  });
  
  // Fonction pour afficher/masquer le formulaire correspondant à l'aide de l'id passé dans le data-attribute
  function toggleForm(event) {
    const targetId = event.currentTarget.getAttribute('data-target');
  
    
    const form = document.getElementById(targetId); //recupere le formulaire qui a l'id du boutton qu'on a récupéré
    
    //switch de display
    if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "flex";
    } else {
        form.style.display = "none";
    }
  }

}



  //----------------------------------page detail projet: navbar----------------------------------

  //les elements de la nav
  const noteBtn = document.querySelector('.open-note') 
  
  //si on trouve cet élément donc que nous sommes sur la page detail projet
  if(noteBtn){

    const commentaireBtn = document.querySelector('.open-commentaire') 
    const actionBtn = document.querySelector('.open-action') 
    
    //les sections
    const noteEl = document.querySelector('.notes')
    const commentaireEl = document.querySelector('.commentaires')
    const actionEl = document.querySelector('.actions')
    
    // par defaut 
    noteEl.classList.add('active')
    noteBtn.classList.add('nav-active')
  
    //fonction montre la div
    function showElement(element, navElement){
      // enleve la classe active à tous les elements et son "btn" de la nav
      noteEl.classList.remove('active')
      commentaireEl.classList.remove('active')
      actionEl.classList.remove('active')
  
      noteBtn.classList.remove('nav-active')
      commentaireBtn.classList.remove('nav-active')
      actionBtn.classList.remove('nav-active')
    
      element.classList.add('active')
      navElement.classList.add('nav-active')
    }
    
    // ecouteurs d'evenement 
  
    noteBtn.onclick = () => {
      showElement(noteEl, noteBtn)
    }
  
    commentaireBtn.onclick = () => {
      showElement(commentaireEl, commentaireBtn)
    }
  
    actionBtn.onclick = () => {
      showElement(actionEl, actionBtn)
    }

  }