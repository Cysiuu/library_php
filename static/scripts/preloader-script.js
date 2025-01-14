const preloaderWrapper = document.querySelector('.preloader-wrapper');
    window.addEventListener('load', () => {
        setTimeout(() => {
            preloaderWrapper.classList.add('fade-out-animation');
         }, 500);
            
    });
