const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

canvas.width = 300;
canvas.height = 500;

// Ball properties
let ball = {
  x: canvas.width / 2,
  y: canvas.height - 30,
  radius: 10,
  dx: 2,
  dy: -2,
};

// Platform properties
const platforms = [
  { x: 50, y: 450, width: 100, height: 10 },
  { x: 150, y: 400, width: 100, height: 10 },
  { x: 80, y: 350, width: 100, height: 10 },
  { x: 120, y: 300, width: 100, height: 10 },
];

// Draw ball function
function drawBall() {
  ctx.beginPath();
  ctx.arc(ball.x, ball.y, ball.radius, 0, Math.PI * 2);
  ctx.fillStyle = "#fff";
  ctx.fill();
  ctx.closePath();
}

// Draw platforms function
function drawPlatforms() {
  ctx.fillStyle = "#000";
  platforms.forEach(platform => {
    ctx.fillRect(platform.x, platform.y, platform.width, platform.height);
  });
}

// Move ball function
function moveBall() {
  ball.x += ball.dx;
  ball.y += ball.dy;

  // Check for wall collisions
  if (ball.x + ball.dx > canvas.width - ball.radius || ball.x + ball.dx < ball.radius) {
    ball.dx = -ball.dx;
  }
  if (ball.y + ball.dy > canvas.height - ball.radius || ball.y + ball.dy < ball.radius) {
    ball.dy = -ball.dy;
  }

  // Check for platform collisions
  platforms.forEach(platform => {
    if (
      ball.x > platform.x &&
      ball.x < platform.x + platform.width &&
      ball.y + ball.radius > platform.y &&
      ball.y - ball.radius < platform.y + platform.height
    ) {
      ball.dy = -ball.dy;
    }
  });
}

// Update game function
function updateGame() {
  ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear canvas
  drawBall();
  drawPlatforms();
  moveBall();

  requestAnimationFrame(updateGame); // Call updateGame again
}

// Start game
updateGame();
