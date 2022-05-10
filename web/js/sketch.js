let message = document.getElementById("leaderb");
let messageX;
const xSpeed = 0.67;
const ySpeed = 0.05;
const amplitude = 80;
const verticalLetterSpacing = 10;
let font = 'arial';




function setup() {
  var myCanvas = createCanvas(100,100);
  myCanvas.parent("cancan");
  textFont(font);
  messageX = width;
}

function draw() {
  background(255,192,0,255);
  fill(35,39,42,255);

  textSize(10);

  for (let i = 0; i < message.length; i++) {
    const letterX = messageX + textWidth(message.substring(0, i));

    const letterOffset = i * verticalLetterSpacing;
    const letterY = height / 1.4 +
      sin((frameCount - letterOffset) * ySpeed) * 20;

    text(message[i], letterX, letterY);
  }

  messageX -= xSpeed;
  if (messageX < - textWidth(message)) {
    messageX = width + 30;
  }

  textSize(10);
  fill(255);
}




