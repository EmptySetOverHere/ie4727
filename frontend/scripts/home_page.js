(function () {
    const slide_images_class_name = ".slider-image";
    const images = document.querySelectorAll(slide_images_class_name);

    let curr = 0;
    let delay = 250; // set delay to the same value as transition duration
    let interval = 5000;
    
    const fade = () => {
        images[curr].style.opacity = 0;
        curr += 1;
        curr %= images.length;

        setTimeout(function () {
            images[curr].style.opacity = 1;
        }, delay);
    }

    setInterval(fade, interval);
})();


(function () {
    const scrolling = document.querySelector('.partners-container-inner');
    const scrolling_parent = scrolling.parentElement;
    const clone = scrolling.cloneNode(true);
    
    scrolling_parent.appendChild(clone); 
})();



