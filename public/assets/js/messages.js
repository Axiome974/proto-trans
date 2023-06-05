const messagesCounter = {

    init: function(){
        console.log("loaded")
        const messagePill = document.querySelector("#message-count");
        const url = messagePill.dataset.path;
        fetch(url)
            .then( response => {
                return response.json()
            })
            .then( json => {
                let count = parseInt(json.messages);
                if( count <= 0){
                    return false;
                }

                if( count > 99){
                    count = "99+";
                }

                messagePill.innerText = count;
                messagePill.classList.remove("d-none")
            })
    }
}
document.addEventListener("DOMContentLoaded", messagesCounter.init );