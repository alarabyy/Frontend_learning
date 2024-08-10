const messageBar = document.querySelector(".bar-wrapper input");
const sendBtn = document.querySelector(".bar-wrapper button");
const messageBox = document.querySelector(".message-box");

const API_URL = "https://api.openai.com/v1/chat/completions";
const API_KEY = "API KEY";

sendBtn.onclick = function () {
  if (messageBar.value.length > 0) {
    const UserTypedMessage = messageBar.value;
    messageBar.value = "";

    let message =
      `<div class="chat message">
      <img src="img/user.jpg">
      <span>${UserTypedMessage}</span>
    </div>`;

    let response =
      `<div class="chat response">
      <img src="img/chatbot.jpg">
      <span class="new">...</span>
    </div>`;

    messageBox.insertAdjacentHTML("beforeend", message);
    messageBox.insertAdjacentHTML("beforeend", response);

    const requestOptions = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${API_KEY}`
      },
      body: JSON.stringify({
        "model": "gpt-3.5-turbo",
        "messages": [{ "role": "user", "content": UserTypedMessage }]
      })
    };

    fetch(API_URL, requestOptions)
      .then(res => res.json())
      .then(data => {
        const ChatBotResponse = document.querySelector(".response .new");
        ChatBotResponse.innerHTML = data.choices[0].message.content;
        ChatBotResponse.classList.remove("new");
      })
      .catch((error) => {
        const ChatBotResponse = document.querySelector(".response .new");
        ChatBotResponse.innerHTML = "Oops! An error occurred. Please try again.";
      });
  }
}
