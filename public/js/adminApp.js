//----------------------------------diagramme chartjs
const ctx = document.getElementById('diag-etat-projet');
var etatProjets = JSON.parse(ctx.dataset.etatProjet);



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