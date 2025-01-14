document.addEventListener('DOMContentLoaded', function() {
    const headers = document.querySelectorAll('h2.display-4');
    headers.forEach(header => {
        const text = header.textContent.toLowerCase().trim();
        const id = 'section-' + text
            .replace(/\s+/g, '-')
            .replace(/ł/g, 'l')
            .replace(/ą/g, 'a')
            .replace(/ę/g, 'e')
            .replace(/ś/g, 's')
            .replace(/ć/g, 'c')
            .replace(/ź/g, 'z')
            .replace(/ż/g, 'z')
            .replace(/ó/g, 'o')
            .replace(/ń/g, 'n');
        header.id = id;
    });

    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.pixel-side-menu');
    
    menuToggle.addEventListener('click', function() {
        menu.classList.toggle('active');
        menuToggle.classList.toggle('active');
    });

    if (window.innerWidth <= 768) {
        const menuLinks = document.querySelectorAll('.pixel-side-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                menu.classList.remove('active');
                menuToggle.classList.remove('active');
            });
        });
    }

    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                const targetPosition = targetSection.offsetTop;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    window.addEventListener('scroll', setActiveLink);
    setActiveLink();
});
