//----------------------------------diagramme chartjs

//constantes des éléments
const ctx = document.getElementById('diag-etat-projet');
const canvasPM = document.getElementById('diag-projet-mois')

//tableaux donnés dans les data-attribute
let etatProjets = JSON.parse(ctx.dataset.etatProjet);
let projetMois = JSON.parse(canvasPM.dataset.projetMois);

//initialise tableaux
let months = []
let counts = []

projetMois.forEach(element => {
    months.push(element.month); //month du tableau associatif
    counts.push(element.count); //count
  });


// diagramme etats projet
new Chart(ctx, {
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