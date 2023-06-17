const quotationModal = {

    modal: document.querySelector("#quotationModal"),
    form: null,

    init: function(){
        const submitButton = quotationModal.modal.querySelector(".btn-success");
        quotationModal.form =  quotationModal.modal.querySelector("form");
        submitButton.addEventListener('click', quotationModal.handleSubmit);

        quotationModal.form.addEventListener('submit', quotationModal.handleSubmit);

    },

    handleSubmit: function( event ){
        event.preventDefault();
        const form = quotationModal.form;

        quotationModal.toggleLoader();


        fetch(form.action, {
            method: "POST",
            body:  new FormData(form)
        })
            .then( response => {
                quotationModal.toggleLoader();
                if( response.ok ){
                    return response.json()
                }
                quotationModal.alertMessage("Les données saisies sont incorrectes");
                throw new Error("Les données saisies sont incorrectes");
            })
            .then( json => {
                form.reset();
                quotationModal.successMessage(json.message);
            })
    },

    alertMessage: function(message){
        const alert = quotationModal.modal.querySelector("#quotation-alert");
        const span = alert.querySelector("span")
        alert.classList.remove("d-none");
        span.textContent = message;
        setTimeout(()=> {
            alert.classList.add("d-none");
        }, 3000)
    },

    successMessage: function(message){
        const success = quotationModal.modal.querySelector("#quotation-success");
        const span = success.querySelector("span")
        success.classList.remove("d-none");
        span.textContent = message;
        setTimeout(()=> {
            success.classList.add("d-none");
        }, 3000)
    },

    toggleLoader: function (){
        quotationModal.modal.querySelector("#quotation-loader").classList.toggle("d-none");
        quotationModal.modal.querySelector("#quotation-loader").classList.toggle("d-flex");
        quotationModal.modal.querySelector("#quotation-form").classList.toggle("d-none");
    }

}

document.addEventListener("DOMContentLoaded", quotationModal.init );