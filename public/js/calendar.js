window.onload = () => {
    let calendarEl = document.querySelector('#calendrier')
    let data = JSON.parse(calendarEl.dataset.calendar)
    let calendarBtn = document.querySelector('#calendar-btn')

    //initilalisation du calendrier avec ses options et le tableau data
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'timeGridWeek,dayGridMonth'
        },
        buttonText: {
            today: 'Aujourdh\'ui',
            month: 'Mois',
            week: 'Semaine',
        },
        slotMinTime: '08:00:00', 
        slotMaxTime: '19:00:00',
        hiddenDays: [ 0 ], //ne pas afficher le dimanche

        events: data,
        editable: true,
        eventResizableFromStart: true,
        eventDrop: (infos) => {
            if(!confirm("Êtes-vous sûr de vouloir modifier cet évènement?")){
                infos.revert()
            }
        }
    })

    //ecouteur d'evenement "au changement", requete ajax avec l'objet XMLHttpRequest qui change directement l'évènement dans la bdd
    calendar.on('eventChange', (e) => {
        // console.log(e)
        let url = `/api/edit/${e.event.id}`
        let donnees = {
            "title": e.event.title,
            "start": e.event.start,
            "end": e.event.end
        };
        // console.log(donnees);
        let xhr = new XMLHttpRequest
        xhr.open("PUT", url)
        xhr.send(JSON.stringify(donnees))
    })

    calendarBtn.addEventListener('onClick', () => {
        console.log("")
    })

    calendar.render()

}


    
