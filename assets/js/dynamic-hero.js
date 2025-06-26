// Dynamic Hero Content JavaScript
document.addEventListener("DOMContentLoaded", () => {
  // Dynamic content arrays
  const titles = [
    "Programmer & Data Scientist",
    "Full-Stack Developer",
    "Machine Learning Expert",
    "Data Analytics Expert",
    "Software Architect",
    "AI Solutions Developer",
  ]

  const descriptions = [
    "Turning data into insights and code into solutions",
    "Building scalable web applications with modern technologies",
    "Creating intelligent systems that learn and adapt",
    "Transforming complex data into actionable business intelligence",
    "Designing robust software architectures for enterprise solutions",
    "Developing cutting-edge AI applications for real-world problems",
  ]

  const images = [
    "https://oumatonny.github.io/Ouma.png",
    "https://i0.wp.com/introtallent.com/wp-content/uploads/2024/01/Top-10-Data-Science-Tools.webp",
    "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR4GoT-aaFK4aL0dVftHK7nnzk4pO41SkNEtQ&s",
    "https://thumbs.dreamstime.com/b/computer-monitor-displaying-lines-colorful-programming-code-possibly-python-computer-monitor-displaying-lines-colorful-373187736.jpg",
    "https://i0.wp.com/mia-platform.eu/wp-content/uploads/Mia-Platform-Software_Architecture_Patterns.png?fit=1794%2C1920&ssl=1",
    "https://miro.medium.com/v2/resize:fit:1400/0*2IFB7Rvv-JD0H-6m"
    
  ]

  let currentIndex = 0
  const titleElement = document.getElementById("dynamicTitle")
  const descriptionElement = document.getElementById("dynamicDescription")
  const imageElement = document.getElementById("heroImage")

  // Preload images
  const preloadedImages = []
  images.forEach((src, index) => {
    const img = new Image()
    img.src = src
    img.onload = () => {
      preloadedImages[index] = img
    }
  })

  function updateContent() {
    // Fade out current content
    titleElement.style.opacity = "0"
    descriptionElement.style.opacity = "0"
    imageElement.style.opacity = "0"

    setTimeout(() => {
      // Update content
      titleElement.textContent = titles[currentIndex]
      descriptionElement.textContent = descriptions[currentIndex]
      imageElement.src = images[currentIndex]

      // Fade in new content
      titleElement.style.opacity = "1"
      descriptionElement.style.opacity = "1"
      imageElement.style.opacity = "1"

      // Move to next index
      currentIndex = (currentIndex + 1) % titles.length
    }, 500)
  }

  // Start the rotation after initial load
  setTimeout(() => {
    setInterval(updateContent, 4000) // Change every 4 seconds
  }, 3000) // Wait 3 seconds before starting

  // Animated counters for stats
  function animateCounters() {
    const counters = document.querySelectorAll(".stat-number")

    counters.forEach((counter) => {
      const target = Number.parseInt(counter.getAttribute("data-target"))
      const duration = 2000 // 2 seconds
      const increment = target / (duration / 16) // 60fps
      let current = 0

      const updateCounter = () => {
        current += increment
        if (current < target) {
          counter.textContent = Math.floor(current)
          requestAnimationFrame(updateCounter)
        } else {
          counter.textContent = target
        }
      }

      updateCounter()
    })
  }

  // Animate skill circles
  function animateSkillCircles() {
    const skillCircles = document.querySelectorAll(".skill-circle")

    skillCircles.forEach((circle) => {
      const percentage = Number.parseInt(circle.getAttribute("data-percentage"))
      const degrees = (percentage / 100) * 360

      setTimeout(() => {
        circle.style.background = `conic-gradient(
                    var(--secondary-color) 0deg,
                    var(--secondary-color) ${degrees}deg,
                    var(--border-color) ${degrees}deg
                )`
      }, 500)
    })
  }

  // Intersection Observer for animations
  const observerOptions = {
    threshold: 0.3,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        if (entry.target.classList.contains("about-stats")) {
          animateCounters()
        }
        if (entry.target.classList.contains("skills-visual")) {
          animateSkillCircles()
        }

        // Add stagger animation to service cards
        if (entry.target.classList.contains("services-grid")) {
          const cards = entry.target.querySelectorAll(".service-card")
          cards.forEach((card, index) => {
            setTimeout(() => {
              card.style.opacity = "1"
              card.style.transform = "translateY(0)"
            }, index * 200)
          })
        }

        observer.unobserve(entry.target)
      }
    })
  }, observerOptions)

  // Observe elements
  const aboutStats = document.querySelector(".about-stats")
  const skillsVisual = document.querySelector(".skills-visual")
  const servicesGrid = document.querySelector(".services-grid")

  if (aboutStats) observer.observe(aboutStats)
  if (skillsVisual) observer.observe(skillsVisual)
  if (servicesGrid) observer.observe(servicesGrid)

  // Initialize service cards as hidden
  const serviceCards = document.querySelectorAll(".service-card")
  serviceCards.forEach((card) => {
    card.style.opacity = "0"
    card.style.transform = "translateY(30px)"
    card.style.transition = "all 0.6s ease"
  })

  // Particle system for hero background
  function createParticles() {
    const particlesContainer = document.querySelector(".particles-container")
    if (!particlesContainer) return

    const particleCount = 50

    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement("div")
      particle.className = "particle"
      particle.style.cssText = `
                position: absolute;
                width: 2px;
                height: 2px;
                background: var(--secondary-color);
                border-radius: 50%;
                opacity: ${Math.random() * 0.5 + 0.2};
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                animation: particleFloat ${Math.random() * 10 + 5}s linear infinite;
                animation-delay: ${Math.random() * 5}s;
            `

      particlesContainer.appendChild(particle)
    }
  }

  // Add particle animation CSS
  const particleCSS = `
        @keyframes particleFloat {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
    `

  const style = document.createElement("style")
  style.textContent = particleCSS
  document.head.appendChild(style)

  // Initialize particles
  createParticles()

  // Tech icons hover effect
  const techItems = document.querySelectorAll(".tech-item")
  techItems.forEach((item) => {
    item.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-5px) scale(1.05)"
    })

    item.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)"
    })
  })

  // Smooth reveal animation for hero elements
  const heroElements = document.querySelectorAll(".hero-text > *")
  heroElements.forEach((element, index) => {
    element.style.opacity = "0"
    element.style.transform = "translateY(30px)"
    element.style.transition = "all 0.8s ease"

    setTimeout(
      () => {
        element.style.opacity = "1"
        element.style.transform = "translateY(0)"
      },
      index * 200 + 500,
    )
  })

  // Image container entrance animation
  const imageContainer = document.querySelector(".image-container")
  if (imageContainer) {
    imageContainer.style.opacity = "0"
    imageContainer.style.transform = "scale(0.8)"
    imageContainer.style.transition = "all 1s ease"

    setTimeout(() => {
      imageContainer.style.opacity = "1"
      imageContainer.style.transform = "scale(1)"
    }, 1000)
  }

  // Add typing effect to the name
  const nameElement = document.querySelector(".name")
  if (nameElement) {
    const originalText = nameElement.textContent
    nameElement.textContent = ""

    let i = 0
    const typeWriter = () => {
      if (i < originalText.length) {
        nameElement.textContent += originalText.charAt(i)
        i++
        setTimeout(typeWriter, 100)
      }
    }

    setTimeout(typeWriter, 1500)
  }

  // Parallax effect for floating icons
  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset
    const floatingIcons = document.querySelectorAll(".floating-icon")

    floatingIcons.forEach((icon, index) => {
      const speed = 0.5 + index * 0.1
      icon.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`
    })
  })

  // Add glowing effect to profile image
  const profileImage = document.querySelector(".profile-image")
  if (profileImage) {
    setInterval(() => {
      profileImage.style.boxShadow = `
                0 0 20px rgba(0, 195, 255, ${Math.random() * 0.5 + 0.3}),
                0 0 40px rgba(255, 0, 255, ${Math.random() * 0.3 + 0.2})
            `
    }, 2000)
  }
})
