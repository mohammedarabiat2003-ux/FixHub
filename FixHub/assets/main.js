document.addEventListener("DOMContentLoaded", function () {

    const revealElements = document.querySelectorAll(".reveal");
    const revealObserver = new IntersectionObserver(
        function (entries) {

            entries.forEach(function (entry) {

                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                    revealObserver.unobserve(entry.target);
                }

            });
        },
        {
            threshold: 0.15
        }
    );

    revealElements.forEach(function (element) {
        revealObserver.observe(element);
    });


    const serviceCards = document.querySelectorAll(".service-card");
    const serviceObserver = new IntersectionObserver(
        function (entries) {

            entries.forEach(function (entry) {

                if (entry.isIntersecting) {
                    serviceCards.forEach(function (card, index) {

                        setTimeout(function () {
                            card.classList.add("show");
                        }, index * 120);
                    });

                    serviceObserver.disconnect();
                }
            });
        },
        {
            threshold: 0.12
        }
    );

    if (serviceCards.length > 0) {
        serviceObserver.observe(serviceCards[0]);
    }


    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".navbar nav a");

    window.addEventListener("scroll", function () {

        let currentSection = "";

        sections.forEach(function (section) {

            const sectionTop = section.offsetTop - 130;
            const sectionHeight = section.offsetHeight;

            if (
                window.scrollY >= sectionTop &&
                window.scrollY < sectionTop + sectionHeight
            ) {
                currentSection = section.getAttribute("id");
            }

        });

        navLinks.forEach(function (link) {
            link.classList.remove("active");

            if (
                link.getAttribute("href") === "#" + currentSection
            ) {
                link.classList.add("active");
            }

        });

    });


    const heroVisual = document.querySelector(".hero-visual");
    const tools = document.querySelectorAll(".tool");

    if (heroVisual && tools.length > 0) {

        heroVisual.addEventListener("mousemove", function (event) {

            const rect = heroVisual.getBoundingClientRect();

            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const moveX = (x - centerX) / 25;
            const moveY = (y - centerY) / 25;

            tools.forEach(function (tool, index) {

                const direction = index % 2 === 0 ? 1 : -1;

                tool.style.marginLeft = moveX * direction + "px";
                tool.style.marginTop = moveY * direction + "px";

            });

        });

        heroVisual.addEventListener("mouseleave", function () {

            tools.forEach(function (tool) {

                tool.style.marginLeft = "0px";
                tool.style.marginTop = "0px";

            });

        });

    }


    const buttons = document.querySelectorAll(
        ".primary-btn, .secondary-btn, .register-btn, .cta a"
    );

    buttons.forEach(function (button) {

        button.addEventListener("click", function (event) {

            const ripple = document.createElement("span");

            ripple.classList.add("button-ripple");

            const rect = button.getBoundingClientRect();

            ripple.style.left = event.clientX - rect.left + "px";
            ripple.style.top = event.clientY - rect.top + "px";

            button.appendChild(ripple);

            setTimeout(function () {
                ripple.remove();
            }, 600);

        });

    });


    document.querySelectorAll('a[href^="#"]').forEach(function (link) {

        link.addEventListener("click", function (event) {

            const targetId = link.getAttribute("href");

            if (targetId === "#") {
                return;
            }

            const target = document.querySelector(targetId);

            if (target) {

                event.preventDefault();

                target.scrollIntoView({
                    behavior: "smooth"
                });
            }

        });

    });

});
