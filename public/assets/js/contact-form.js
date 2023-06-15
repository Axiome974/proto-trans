const contactForm = {

    init: function(){
        console.log("loaded")
        const form = document.querySelector("#contact-us form");
        form.addEventListener("submit", contactForm.handleContactForm );
    },

    handleContactForm: function(event){
        event.preventDefault();
        const form = event.target;
        const button = form.querySelector("button");
        button.disabled = true;
        const buttonText = button.textContent;
        button.innerHTML =  `<div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`


        const formData = new FormData(form);
        const url = form.action;
        fetch(url, {
            method: "POST",
            body: formData
        })
            .then( response => {
                button.innerText = buttonText;
                button.disabled = false;
                return response.json()
            })
            .then( json => {
                form.reset();
                alert(json.message);
            })
    }
}

document.addEventListener("DOMContentLoaded", contactForm.init );