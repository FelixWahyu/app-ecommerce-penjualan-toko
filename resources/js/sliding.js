let currentIndex = 0;

function showSlide(index) {
    const slider = document.getElementById("slider");
    const maxIndex = slider.children.length - 1;

    if (index < 0) currentIndex = maxIndex;
    else if (index > maxIndex) currentIndex = 0;
    else currentIndex = index;

    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
}

function nextSlide() {
    showSlide(currentIndex + 1);
}

function prevSlide() {
    showSlide(currentIndex - 1);
}

setInterval(() => {
    nextSlide();
}, 5000);

window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
