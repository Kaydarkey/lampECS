window.onload = function() {
    var canvas = document.getElementById("confetti");
    var confettiSettings = { target: canvas, max: 150, size: 1.2, clock: 25 };
    var confetti = new ConfettiGenerator(confettiSettings);
    confetti.render();
};
