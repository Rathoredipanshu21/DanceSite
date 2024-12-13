const toggleIcon = document.getElementById('toggle-icon');
const offerPanel = document.getElementById('offer-panel');

function toggleOffer() {
  const showOffer = offerPanel.classList.toggle('show');
  toggleIcon.className = showOffer ? 'fa-solid fa-times' : 'fa-solid fa-bars';
}



//about
gsap.registerPlugin(ScrollTrigger);

gsap.to(".about", {
  opacity: 1,
  scale: 1,
  duration: 1.5,
  ease: "power4.out",
  scrollTrigger: {
    trigger: ".about",
    start: "top 75%",
    end: "bottom 25%",
    scrub: 1,
    toggleActions: "play none none reverse"
  }
});


//main
const lenis = new Lenis()

lenis.on('scroll', (e) => {
  console.log(e)
})

function raf(time) {
  lenis.raf(time)
  requestAnimationFrame(raf)
}

requestAnimationFrame(raf)

let tl = gsap.timeline({
  scrollTrigger: {
      trigger: '.two',
      start: '20% 50%',
      end: '100% 50%',
      scrub: 2,  
  }
});

tl.to(".text-area-hover", {
  width: "100%",
  duration: 5 
});







document.querySelector('.dance-video').addEventListener('click', function() {
  this.src += '';
});










