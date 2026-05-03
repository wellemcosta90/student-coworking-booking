// simple intro screen only for the home page
document.addEventListener("DOMContentLoaded", function () {
    var path = window.location.pathname;

    if (path != "/student-coworking-booking/" && path != "/student-coworking-booking/index.php") {
        return;
    }

    var intro = document.createElement("div");
    intro.className = "intro-screen";
    intro.innerHTML =
        "<div class='intro-title'>Student Coworking</div>" +
        "<div class='intro-subtitle'>Scroll down to enter</div>";

    document.body.appendChild(intro);
    document.body.classList.add("intro-running");

    function closeIntro() {
        intro.classList.add("intro-hide");
        document.body.classList.remove("intro-running");

        setTimeout(function () {
            intro.remove();
        }, 900);
    }

    // source for wheel event: https://developer.mozilla.org/en-US/docs/Web/API/Element/wheel_event
    intro.addEventListener("wheel", function (event) {
        if (event.deltaY > 0) {
            closeIntro();
        }
    });

    // source for touch events: https://developer.mozilla.org/en-US/docs/Web/API/Touch_events
    var startY = 0;
    intro.addEventListener("touchstart", function (event) {
        startY = event.touches[0].clientY;
    });

    intro.addEventListener("touchend", function (event) {
        var endY = event.changedTouches[0].clientY;
        if (startY - endY > 30) {
            closeIntro();
        }
    });

    intro.addEventListener("click", closeIntro);
});
