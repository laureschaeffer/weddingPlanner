window.onload = () => {
    let calendarEl = document.querySelector('#calendrier') //element dom calendrier
    let data = JSON.parse(calendarEl.dataset.calendar) //données dans les data-attribute

    let modal = document.querySelector("#modal"); //modal
    let closeModal = document.querySelector('.close') //ferme le modal
    
    //form et ses 4 input 
    let formEvent = document.querySelector('#new-event')
    let inputTitle = document.querySelector(".title");
    let inputStart = document.querySelector(".start");
    let inputEnd = document.querySelector(".end");
    let inputUser = document.querySelector(".users");
        

    //----------------initilalisation du calendrier avec ses options et le tableau data----------------
    // requete ajax avec l'objet XMLHttpRequest qui change directement l'évènement dans la bdd
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        //boutons custom qui ouvre le modal
        customButtons: {
            customAddEvent: {
                text: 'Créer un rendez-vous',
                click: function() {
                    modal.style.display = "block"; 
                }
            }
        },
        headerToolbar: {
            start: 'prev,next today',
            center: 'customAddEvent',
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
        },
        eventClick: (infos) => {
            if(!confirm("Êtes-vous sûr de vouloir supprimer cet évènement?")){
                infos.revert()
            } else {
                infos.event.remove()
                deleteEvent(infos)
            }
        }
    })

    // ----------------modifie un evenement----------------  
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

        // valeurs des 4 input
        let titleValue = inputTitle.value;
        let startValue = inputStart.value;
        let endValue = inputEnd.value;
        let userValue = inputUser.value;

        if(
            titleValue && titleValue !== "" &&
            startValue && startValue !== "" &&
            endValue && endValue !== "" &&
            userValue && userValue !== ""
        ){
            if(startValue < endValue){
                let donnees = {
                    title: titleValue,
                    start: startValue,
                    end: endValue,
                    user: userValue
                };
                
                let url = '/api/post';
        
                let xhr = new XMLHttpRequest();
                xhr.open("POST", url);
            
        
                xhr.send(JSON.stringify(donnees));
                modal.style.display = "none";
                
            } else {
                alert("Veuillez sélectionner une date valide!")
            }
        } else {
            alert("Veuillez remplir tous les champs!")
        }

    }    

    // ----------------supprime un evenement---------------- 
    function deleteEvent(infos) {
       
        let url = `/api/delete/${infos.event.id}`;

        let xhr = new XMLHttpRequest()
        xhr.open("DELETE", url)

        xhr.send(null)
    }

    calendar.render()

}


    
