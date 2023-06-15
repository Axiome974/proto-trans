const messagesCounter = {

    init: function(){
        console.log("loaded")
        const messagePill = document.querySelector("#message-count");
        const quotationPill = document.querySelector("#devis-count");
        const url = messagePill.dataset.path;
        fetch(url)
            .then( response => {
                return response.json()
            })
            .then( json => {
                messagesCounter.updatePill("#message-count", parseInt(json.messages));
                messagesCounter.updatePill("#devis-count", parseInt(json.quotations));
            })
    },

    updatePill: function(pill, value){
        const pillElement = document.querySelector(pill);
        if( value <= 0){
            return false;
        }
        if( value > 99){
            value = "99+";
        }
        pillElement.innerText = value;
        pillElement.classList.remove("d-none")
    }
}
document.addEventListener("DOMContentLoaded", messagesCounter.init );