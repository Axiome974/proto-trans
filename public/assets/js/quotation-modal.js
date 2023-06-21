const quotationModal = {

    modal: document.querySelector("#quotationModal"),
    form: null,

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
                    return response.text()
                }
            })
            .then( html => {
                const modalBody = document.querySelector(".modal-body-content");
                modalBody.innerHTML = html;
                modalLoader.applyFormEventListener();
            })
    },

    toggleLoader: function (){
        quotationModal.modal.querySelector("#quotation-loader").classList.toggle("d-none");
        quotationModal.modal.querySelector("#quotation-loader").classList.toggle("d-flex");
        quotationModal.modal.querySelector("#quotation-form").classList.toggle("d-none");
    }

}

