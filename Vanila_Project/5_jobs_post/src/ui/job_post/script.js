function toggleDetails() {
  const details = document.getElementById('job-details');
  details.style.display = details.style.display === 'none' || details.style.display === '' ? 'block' : 'none';
}

function toggleComments() {
  const commentsContainer = document.getElementById('comments-container');
  commentsContainer.style.display = commentsContainer.style.display === 'none' || commentsContainer.style.display === '' ? 'block' : 'none';
}

function openModal() {
  const modal = document.getElementById('applyModal');
  modal.style.display = 'block';
}

function closeModal() {
  const modal = document.getElementById('applyModal');
  modal.style.display = 'none';
}

window.onclick = function (event) {
  const modal = document.getElementById('applyModal');
  if (event.target == modal) {
    modal.style.display = 'none';
  }
};

