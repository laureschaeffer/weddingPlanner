window.onload = () => {
    let calendarEl = document.querySelector('#calendrier') //element dom calendrier
    let data = JSON.parse(calendarEl.dataset.calendar) //données dans les data-attribute

    let calendarBtn = document.querySelector('#calendar-btn') //bouton qui ouvre le modal
    let modal = document.querySelector("#modal"); //modal
    let closeModal = document.querySelector('.close') //ferme le modal
    
    //form et ses 3 input 
    let formEvent = document.querySelector('#new-event')
    let inputTitle = document.querySelector(".title");
    let inputStart = document.querySelector(".start");
    let inputEnd = document.querySelector(".end");
        

    //----------------initilalisation du calendrier avec ses options et le tableau data----------------
    // requete ajax avec l'objet XMLHttpRequest qui change directement l'évènement dans la bdd
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
    });


    // ----------------ajoute un nouvel evenement----------------
    calendarBtn.onclick =  () => {
        modal.style.display = "block";  
    }
    
    // ferme le modal avec le bouton ou en cliquant ailleurs
    closeModal.onclick = () => {
        modal.style.display = "none"; 
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    //validation du formulaire
    formEvent.onsubmit = (e) => {
        e.preventDefault(); //empeche la page de recharger

        // valeurs des 3 input
        let titleValue = inputTitle.value;
        let startValue = inputStart.value;
        let endValue = inputEnd.value;

        let donnees = {
            title: titleValue,
            start: startValue,
            end: endValue
        };
        
        let url = '/api/post';

        let xhr = new XMLHttpRequest();
        xhr.open("POST", url);
    

        xhr.send(JSON.stringify(donnees));

    }    

    calendar.render()

}


    
