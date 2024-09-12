window.onload = () => {
    let calendarEl = document.querySelector('#calendrier') //element dom calendrier
    let data = JSON.parse(calendarEl.dataset.calendar) //données dans les data-attribute

    let modalNew = document.querySelector(".modal.new-event"); //modal nouveau evenement
    let modalShow = document.querySelector(".modal.show-event"); //modal montre l'evenement
    let closeModal = document.querySelector('.close') //ferme le modal
    let closeShowModal = document.querySelector('.close.showModal') //ferme le show modal
    // paragraphes qui contiendront le titre et le user dynamiquement
    let titleModal = document.querySelector('.modalTitle')    
    let userModal = document.querySelector('.modalUser')
    let deleteBtnModal = document.querySelector('.modalDeleteBtn') //btn qui supprimera l'évènement
    
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
                    modalNew.style.display = "block"; 
                }
            }
        },
        headerToolbar: {
            start: 'prev,next today',
            center: 'customAddEvent',
            end: 'listWeek,timeGridWeek,dayGridMonth'
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        slotMinTime: '08:00:00', 
        slotMaxTime: '19:00:00',
        allDaySlot: false,
        hiddenDays: [ 0 ], //ne pas afficher le dimanche

        events: data,
        editable: true,
        eventResizableFromStart: true,
        //-----evenements-----
        eventDrop: (infos) => {
            if(!confirm("Êtes-vous sûr de vouloir modifier cet évènement?")){
                infos.revert()
            }
        },
        eventClick: (infos) => {
            showInfoModal(infos)
        }
    })

    //--------------affiche les infos--------------
    function showInfoModal(infos){
        let selectedEvent = infos.event
        modalShow.style.display = "block"
        // ajoute dynamiquement les infos 
        titleModal.insertAdjacentHTML("beforeend", infos.event.title)             
        userModal.insertAdjacentHTML("beforeend", infos.event.extendedProps.user) 

        // ferme le modal avec le bouton ou en cliquant ailleurs
        closeShowModal.onclick = () => {
            modalShow.style.display = "none"; 
        }
        window.onclick = (event) => {
            if (event.target == modalShow) {
                modalShow.style.display = "none";
            }
        }

        // -----bouton dans le modal pour supprimer l'evenement
        deleteBtnModal.onclick = (event) => {
            if(!confirm("Êtes-vous sûr de vouloir supprimer cet évènement?")){
                    return
                } else {
                    selectedEvent.remove()                    
                    modalShow.style.display = "none";
                    deleteEvent(selectedEvent)
                }

        }

    }

    // ----------------modifie un evenement----------------  
    //ecouteur d'evenement "au changement", requete ajax avec l'objet XMLHttpRequest qui change directement l'évènement dans la bdd
    calendar.on('eventChange', (e) => {
        // console.log(e)
        let url = `/coiffe/rendez-vousEdit/${e.event.id}`
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
        modalNew.style.display = "none"; 
    }

    window.onclick = (event) => {
        if (event.target == modalNew) {
            modalNew.style.display = "none";
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
                //ajoute l'évènement en direct mais ne l'enregistre pas en base de données
                calendar.addEvent({
                    title: titleValue,
                    start: startValue,
                    end: endValue,
                    backgroundColor: '#2c3e50',
                    borderColor: '#ffff',
                    textColor: '#ffff',
                })
                let url = '/coiffe/rendez-vousPost';
        
                let xhr = new XMLHttpRequest();
                xhr.open("POST", url);
            
        
                xhr.send(JSON.stringify(donnees));
                modalNew.style.display = "none"; //ferme le modal
                
            } else {
                alert("Veuillez sélectionner une date valide!")
            }
        } else {
            alert("Veuillez remplir tous les champs!")
        }

    }    

    // ----------------supprime un evenement---------------- 
    function deleteEvent(selectedEvent) {
       
        let url = `/coiffe/rendez-vousDelete/${selectedEvent.id}`;

        let xhr = new XMLHttpRequest()
        xhr.open("DELETE", url)

        xhr.send(null)
    }

    calendar.render()

}


    
