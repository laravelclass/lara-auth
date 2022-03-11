class LaraAuthAjax{
    inputs;
    #logic;
    constructor(logic , formParams = []) {
        this.inputs = formParams;
        this.#logic = logic;
        this.#setEvents();
    }
    #makeHxr(map)
    {
        return new Promise((resolve , reject) => {
            const xhr = new XMLHttpRequest();

            const formData = new FormData();

            if (this.inputs.length > 0)
            {
                for (const input of map)
                {
                    formData.set(input[0] , input[1]);
                }
            }

            xhr.open('POST', document.forms[0].getAttribute('action'));

            xhr.setRequestHeader('X-CSRF-TOKEN',document.querySelector("meta[name = X-CSRF-TOKEN]").getAttribute('content'));

            xhr.onload = function (){
                if (this.status === 200)
                {
                    resolve(this.response);
                }
                else
                {
                    reject(this.response);
                }
            }

            xhr.send(formData);


        });
    }
    #setEvents(){
        document.forms[0].addEventListener('submit',(e) => {
            e.preventDefault();
            const map = new Map();
            if (this.inputs.length > 0)
            {
                this.inputs.forEach(el => {

                    const element = document.getElementsByName(el)[0];

                    if (element.getAttribute('type') === "checkbox" && element.checked !== true)
                    {
                        element.value = null;
                    }

                    map.set(el ,document.getElementsByName(el)[0].value)
                })
            }
            this.init(map);
        })
    }
    async init(map){
        const laraAuthResponse = await this.#makeHxr(map);

        this.#logic(laraAuthResponse);
    }
}


