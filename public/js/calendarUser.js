window.onload = () => {
    let calendarEl = document.querySelector('#calendrier') //element dom calendrier
    let data = JSON.parse(calendarEl.dataset.calendar) //données dans les data-attribute

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
        hiddenDays: [ 0 ], //ne pas afficher le dimanche

        events: data,
        selectable: true,
        select: (info) => {
            let title = prompt('Veuillez entrer le sujet du rendez-vous :');
            if(title){
                createEvent(info, title)
            }
        },
    })

    // ----------------ajoute un nouvel evenement----------------   
    function createEvent(info, title) {
        //ajoute l'évènement en direct
        calendar.addEvent({
            title: "",
            start: info.startStr,
            end: info.endStr,
            backgroundColor: '#b3b9c1',
            borderColor: '#b3b9c1',
            textColor : '#000',
        });

        // requete ajax pour la bdd 
        let newEventDonnees = {
            title: title,
            start: info.startStr,
            end: info.endStr
        }
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/rendez-vous/new")
        
        xhr.send(JSON.stringify(newEventDonnees))

        calendar.unselect(); // désélectionne
    }

    calendar.render()
}