document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("roleSelect");
    const pilotesContainer = document.getElementById("pilotesContainer");

    if (!roleSelect || !pilotesContainer) return;

    function togglePilotes() {
        if (roleSelect.value === "0") {
            pilotesContainer.style.display = "block";
        } else {
            pilotesContainer.style.display = "none";
        }
    }

    roleSelect.addEventListener("change", togglePilotes);
    togglePilotes();
});