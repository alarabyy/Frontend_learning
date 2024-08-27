const API = 'http://localhost/'

const mainEl = document.getElementById("MainBody")
const loginEl = document.getElementById("LoginForm")
const ErrBox = document.getElementById("ErrorBox")

//console.log(document.cookie.match(/^(.*;)?\s*PHPSESSID\s*=\s*[^;]+(.*)?$/))
// helpers
function SwitchForms() {
    ErrBox.innerHTML = ''
    mainEl.classList.toggle('registering')
    return false
}
function redirectToPage(from, to = "index.html") {
    window.location.href = window.location.href.replace(from, to)
}
function HandleErrors(errs) {
    ErrBox.innerHTML = ''
    if(Array.isArray(errs)) {
        for(err of errs) {
            ErrBox.innerHTML += '<p>' + err + '<?p>'
        }
        return
    }
    ErrBox.innerHTML+= '<p>' + errs + '<?p>'
}

window.onload = () => {
    if(window.localStorage.getItem("userToken") !== null) {
        redirectToPage('login.html')
    }
}
// calls
async function OnLogin(e) {
    e.preventDefault()
    let formData = new FormData(e.target);
    
    const response = await fetch(API + 'login', {
        credentials: "same-origin",
        method: "POST",
        body: formData
    })
    res = await response.json()

    if(!res['status']) {
        HandleErrors(res['data'])
        return
    }
    window.localStorage.setItem("userToken", res['data'])

    redirectToPage("login.html");
}

async function OnRegister(e) {
    e.preventDefault()

    let formData = new FormData(e.target);
    if(!formData.has("is_recruiter")) {
        formData.append("is_recruiter", 0)
    } else {
        formData.set("is_recruiter", 1)
    }
    const response = await fetch(API + 'register', {
        method: "POST",
        body: new URLSearchParams(formData),
    })
    res = await response.json()

    if(!res['status']) {
        HandleErrors(res['data'])
        return
    }

    //redirectToPage("login.html");
    SwitchForms()
}