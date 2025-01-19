// Live wedstrijden vernieuwen
function refreshLiveMatches() {
    fetch('includes/live-matches.php')
        .then(response => response.text())
        .then(html => {
            document.querySelector('.live-matches-container').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}

// Ververs elke 60 seconden
setInterval(refreshLiveMatches, 60000);

// Club slideshow functionaliteit
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.club-slide');
    const dots = document.querySelectorAll('.club-dot');
    const prevButton = document.querySelector('.club-prev');
    const nextButton = document.querySelector('.club-next');
    let currentSlide = 0;
    const slideCount = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = '0';
            slide.style.transform = 'translateX(100%)';
            dots[i].classList.remove('bg-blue-500', 'w-8');
            dots[i].classList.add('bg-white/30');
        });

        slides[index].style.opacity = '1';
        slides[index].style.transform = 'translateX(0)';
        dots[index].classList.remove('bg-white/30');
        dots[index].classList.add('bg-blue-500', 'w-8');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slideCount;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slideCount) % slideCount;
        showSlide(currentSlide);
    }

    // Event listeners
    nextButton?.addEventListener('click', nextSlide);
    prevButton?.addEventListener('click', prevSlide);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Automatische slideshow
    setInterval(nextSlide, 5000);

    // Start met de eerste slide
    showSlide(0);
});

// League tabs functionaliteit
function switchLeague(leagueId) {
    const tabs = document.querySelectorAll('.league-tab');
    const contents = document.querySelectorAll('.league-content');

    tabs.forEach(tab => {
        if (tab.dataset.league === leagueId) {
            tab.classList.add('active', 'bg-blue-600', 'text-white');
        } else {
            tab.classList.remove('active', 'bg-blue-600', 'text-white');
        }
    });

    contents.forEach(content => {
        if (content.id === `${leagueId}-clubs`) {
            content.classList.remove('hidden');
        } else {
            content.classList.add('hidden');
        }
    });
} 