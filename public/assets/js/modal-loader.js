const modalLoader = {

    init: function(){
        const modalButtons = document.querySelectorAll(".quotation-modal-btn");

        for( const modalButton of modalButtons){
            modalButton.addEventListener("click", modalLoader.handleClick);

        }

    },

    handleClick: function(event){
        modalLoader.toggleModalLoader();
        const path = event.target.dataset.controller;
        fetch(path)
            .then( response => {
                if(response.ok){
                    return response.text();
                }
            })
            .then( html => {
                const modalBody = document.querySelector(".modal-body-content");
                modalLoader.toggleModalLoader();
                modalBody.innerHTML = html;
                modalLoader.applyFormEventListener();
            })
    },

    toggleModalLoader: function(){
        const modalLoader = document.querySelector("#modal-loader");
        const modalFooter = document.querySelector(".modal-footer");


        modalLoader.classList.toggle("d-none");
        modalFooter.classList.toggle("d-none");

    },

    applyFormEventListener: function(){
        quotationModal.form =  quotationModal.modal.querySelector("form");
        quotationModal.form.addEventListener('submit', quotationModal.handleSubmit);
    }



}

document.addEventListener("DOMContentLoaded", modalLoader.init );