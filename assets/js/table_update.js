document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll(".edit-btn");
    const saveButtons = document.querySelectorAll(".save-btn");
    const seatsInputs = document.querySelectorAll(".seats");
    const locationSelects = document.querySelectorAll(".location");
    const smokingInputs = document.querySelectorAll(".smoking");

    //Attach event listeners to each edit and save button
    editButtons.forEach((editButton, index) => {
        editButton.addEventListener("click", function() {
            seatsInputs[index].removeAttribute("disabled");
            locationSelects[index].removeAttribute("disabled");
            smokingInputs[index].removeAttribute("disabled");
            saveButtons[index].style.display = "inline";
            editButton.style.display = "none";
        });
    });

    saveButtons.forEach((saveButton, index) => {
        saveButton.addEventListener("click", function() {
            const tableId = saveButton.getAttribute("data-table-id");
            const newSeats = seatsInputs[index].value;
            const newLocation = locationSelects[index].value;
            const newSmoking = smokingInputs[index].value;

            //Sends json to update_table.php
            fetch("update_table.php", {
                method: "POST",
                body: JSON.stringify({ tableId, newSeats, newLocation, newSmoking }),
                headers: {
                    "Content-Type": "application/json"
                }
            })
                .then(response => response.json())
                .catch(error => {
                    console.error("Error:", error);
                });

            seatsInputs[index].setAttribute("disabled", "true");
            locationSelects[index].setAttribute("disabled", "true");
            smokingInputs[index].setAttribute("disabled", "true");
            saveButton.style.display = "none";
            editButtons[index].style.display = "inline";
        });
    });
});
//# sourceMappingURL=popper.js.map
