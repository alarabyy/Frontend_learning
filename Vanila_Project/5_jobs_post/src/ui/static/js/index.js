const API = 'http://localhost/'

const profileMenu = document.getElementById("profileMenu")
const navbar = document.getElementById("Navbar")
const PostsList = document.getElementById("PostsList")
const applyModal = document.getElementById('applyModal');
const popup = document.getElementById("popup");
const postTemplate = PostsList.children[0]
const commentTemplate = postTemplate.querySelector('.comment')

var userToken = window.localStorage.getItem("userToken")
var selectedJob = null

PostsList.children[0].remove()
postTemplate.querySelector('.comments-section').innerHTML = ''

window.onload = async function() {
    if(!IsAuthenticated()) {
        redirectToPage('index', 'login')
    }
    let feed = await GetFeed()
    feed = feed.data
    let posts = feed['posts']
    if(posts != null) {
        for (let i = 0; i < posts.length; i++) {
            ShowPost(posts[i])
        }
    }
};
function ShowPost(post) {
    const el = postTemplate.cloneNode(true)
        
    el.querySelector(".postPosition").innerHTML = post['position']
    el.querySelector(".company").innerHTML = `${post['company']} <span class="location">- ${post['location']}</span>`
    el.querySelector(".posted").innerHTML = `posted ${post['created_at']}`
    el.querySelector(".salary-value").innerHTML = post['salary'] != 0 ? post['salary'] + '$' : 'Confidential'
    el.querySelector(".industry-value").innerHTML = post['industry']
    el.querySelector(".description").innerHTML = post['description']
    el.querySelector(".apply-btn").addEventListener('click', (e) => {
        OpenModal(post['id'])
    })
    el.querySelector(".apply-btn").addEventListener('click', (e) => {
        OpenModal(post['id'])
    })
    el.querySelector(".details-btn").addEventListener('click', (e) => {
        toggleDetails(el)
    })
    el.querySelector(".comments-show").addEventListener('click', async (e) => {
        toggleComments(el)
        await ShowComments(post, el)
    })
    var postId = post.id
    el.querySelector('#addCommentInput').addEventListener("keypress", async function(event) {
        if (event.key === "Enter") {
            event.preventDefault()
            const content = event.target.value
            console.log(postId);
            let comment = await SendComment(post.id, content)
            AddComment(el.querySelector('.comments-section'), comment['data'])
        }
        })
    PostsList.appendChild(el);
}
async function OnAddNewPost(e) {
    e.preventDefault()
    
    var res = await AddPost(e)
    if(res['status'] == false) {
        console.log("error")
        return
    }
    var post = res.data
    ShowPost(post)
    closeCreatePostPopup()
}
function toggleMenu() {
    var menu = document.getElementById("navbarMenu");
    if (menu.style.display === "block") {
      menu.style.display = "none";
    } else {
      menu.style.display = "block";
    }
  }
// function toggleMenu() {
//     profileMenu.classList.toggle("open-menu");
// }
function IsAuthenticated() {
    return userToken !== null
}
function SwitchOnline() {
    navbar.classList.toggle('online')
}
function redirectToPage(from, to = "index.html") {
    window.location.href = window.location.href.replace(from, to)
}
async function ShowComments(post, parent) {
    const commentsContainer = parent.querySelector('.comments-section')
    let comments = await GetComments(post)
    comments = comments.data
    
    if(comments == null) {
        return
    }
    for (let i = 0; i < comments.length; i++) {
        AddComment(commentsContainer, comments[i])
    }
}
function AddComment(commentsContainer, comment) {
    const el = commentTemplate.cloneNode(true)
    el.querySelector('.comment-user-name').innerHTML = `${comment['auther']['Fame']} ${comment['auther']['Lname']}`
    el.querySelector('.comment-user-title').innerHTML = comment['auther']['title']
    el.querySelector('.comment-body').innerHTML = comment['content']
    el.querySelector('.comment-time').innerHTML = comment['created_at']
    commentsContainer.appendChild(el);
}
async function SendComment(postId, content) {
    let formData = new FormData();
    formData.append('Authorization', `Bearer ${userToken}`)
    formData.append('post_id', postId)
    formData.append('content', content)
    const response = await fetch(API + 'post/add/comment', {
        credentials: "same-origin",
        method: "POST",
        body: formData
    })
    return response.json();
}
// post model
function toggleDetails(parent) {
    const details = parent.querySelector('#job-details');
    details.style.display = details.style.display === 'none' || details.style.display === '' ? 'block' : 'none';
}

function toggleComments(parent) {
    const commentsContainer = parent.querySelector('#comments-container');
    commentsContainer.style.display = commentsContainer.style.display === 'none' || commentsContainer.style.display === '' ? 'block' : 'none';
}
async function SubmitCurrentJobRequest() {
    let res = await AddApply()
    applyModal.querySelector('.msg').innerHTML = res['data']
}
function OpenModal(id) {
    selectedJob = id
    applyModal.style.display = 'block';
}

function closeModal() {
applyModal.style.display = 'none';
}

window.onclick = function (event) {
if (event.target == applyModal) {
    applyModal.style.display = 'none';
}
};
// calls
async function AddApply() {
    let formData = new FormData();
    formData.append('Authorization', `Bearer ${userToken}`)
    formData.append('post_id', selectedJob)
    const response = await fetch(API + 'post/apply', {
        credentials: "same-origin",
        method: "POST",
        body: formData
    })
    return response.json()
}
async function GetFeed() {
    let formData = new FormData();
    formData.append('Authorization', `Bearer ${userToken}`)
    const response = await fetch(API + 'feed', {
        credentials: "same-origin",
        method: "POST",
        body: formData
    })
    return response.json()
}
async function AddPost(e) {
    let formData = new FormData(e.target);
    formData.append('Authorization', `Bearer ${userToken}`)
    const response = await fetch(API + 'post/add', {
        credentials: "same-origin",
        method: "POST",
        body: formData
    })
    return response.json()
}
async function GetComments(post) {
    let formData = new FormData();
    formData.append('Authorization', `Bearer ${userToken}`)
    formData.append('id', post.id)
    const response = await fetch(API + 'post/comments', {
        credentials: "same-origin",
        method: "POST",
        body: formData
    })
    return response.json()
}
async function Logout() {
    toggleMenu()
    let formData = new FormData();
    formData.append('Authorization', `Bearer ${userToken}`)
    const response = await fetch(API + 'logout', {
        credentials: "same-origin",
        method: "POST",
        body: formData
    })
    let res = response.json()
    if(res['status'] == false) {
        console.log('logout failed')
        return
    }
    window.localStorage.removeItem('userToken')
    userToken = null
    redirectToPage('index', 'login')
}

document.addEventListener("DOMContentLoaded", () => {
    const openPopupBtn = document.getElementById("openPopup");
    const closeBtn = document.querySelector(".close");

    openPopupBtn.addEventListener("click", () => {
      popup.style.display = "block";
    });

    closeBtn.addEventListener("click", () => {
        closeCreatePostPopup()
    });

    window.addEventListener("click", (event) => {
      if (event.target === popup) {
        closeCreatePostPopup()
      }
    });
  });

  function closeCreatePostPopup() {
    popup.style.display = "none";
  }