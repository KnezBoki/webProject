function getReservationType(time) {
    if (time >= 700 && time <= 1100) {
        return 'Breakfast';
    } else if (time >= 1200 && time <= 1600) {
        return 'Lunch';
    } else if (time >= 1800 && time <= 2200) {
        return 'Dinner';
    }
    return 'Unknown';
}
function cancelReservation(reservationId) {
    let formData = new FormData();
    formData.append("reservationId", reservationId);

    fetch("cancel_reservations.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(data => {

            let cancelForm = document.querySelector("#cancelReservation_" + reservationId);
            if (cancelForm) {
                let reservationDiv = cancelForm.closest(".col-sm-4");
                if (reservationDiv) {
                    reservationDiv.classList.add("d-none");
                }
            }

            let successPopup = document.querySelector("#successPopup");
            successPopup.style.zIndex = "2000";
            successPopup.classList.remove("d-none");
            successPopup.style.display = "block";

            setTimeout(function() {
                successPopup.style.display = "none";
                successPopup.classList.add("d-none");
                successPopup.style.zIndex = "1000";
            }, 3000); // 3 second delay in MILLISECONDS
        })
        .catch(error => {
            console.error("Error:", error);
        });
}
function updateReservationStatus(reservationId, newStatus) {
    let workerComment = document.querySelector(".workerComment").value;
    let formData = new FormData();
    formData.append("reservationId", reservationId);
    formData.append("newStatus", newStatus);
    formData.append("workerComment", workerComment);

    fetch("update_reservations.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            let successPopup = document.querySelector("#successPopup");
            successPopup.style.zIndex = "2000";
            successPopup.classList.remove("hidden");
            successPopup.style.display = "block";

            setTimeout(function() {
                successPopup.style.display = "none";
                successPopup.classList.add("hidden");
                successPopup.style.zIndex = "1000";
            }, 2000); // 3 second delay in MILLISECONDS
        })
        .catch(error => {
            console.error("Error:", error);
        });
}