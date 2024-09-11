window.onload = () => {
  
    const today = new Date();
    const threeMonthsLater = new Date().setMonth(today.getMonth() + 3); //date dans 3 mois
    
    let calendarEl = document.querySelector('#calendrier') //element dom calendrier
    let eventsJSON = JSON.parse(calendarEl.dataset.calendarJson) //tableau des evenements

    
    //fonction pour savoir si une date est dispo
    function isEventAvailable(info, eventsJSON) {
        let errors = 0
        eventsJSON.forEach(event => {
            
            let existingStart = new Date(event.start).getTime()
            let existingEnd = new Date(event.end).getTime()
            let newStart = new Date(info.startStr).getTime()
            let newEnd = new Date(info.endStr).getTime()
            
            //si un créneau dépasse, incrémente 
            if (newStart < existingEnd && newEnd > existingStart) {
                errors +=1
            } 

        })

        return errors == 0
      }

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        headerToolbar: {
            start: 'today',
            end: 'prev,next'
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine'
        },
        slotMinTime: '08:00:00', 
        slotMaxTime: '19:00:00',
        //affiche de la date d'ajd à dans 3 mois
        validRange: {
            start: new Date(),
            end: threeMonthsLater
          },
        hiddenDays: [ 0 ], //ne pas afficher le dimanche

        events: eventsJSON,
        selectable: true,
        select: (info) => {
            let title = prompt('Veuillez entrer le sujet du rendez-vous :');
            if(title){
                createEvent(info, title)
            } else {
                alert('Veuillez entrer un sujet!')
            }
        },
    })

    // ----------------ajoute un nouvel evenement----------------   
    function createEvent(info, title) {

        if(isEventAvailable(info, eventsJSON)){
            // ajoute l'évènement en direct
            calendar.addEvent({
                title: "",
                start: info.startStr,
                end: info.endStr,
                backgroundColor: '#b3b9c1',
                borderColor: '#b3b9c1',
                textColor : '#000',
            });
    
            // // requete ajax pour la bdd 
            let newEventDonnees = {
                title: title,
                start: info.startStr,
                end: info.endStr
            }
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/rendez-vous/new")
            
            xhr.send(JSON.stringify(newEventDonnees))

        } else {
            alert("Veuillez choisir une date disponible!");
            
        }

        calendar.unselect(); // désélectionne
    }

    calendar.render()
}