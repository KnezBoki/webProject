document.addEventListener("DOMContentLoaded", function () {
    // Edit worker
    const editWorkerButtons = document.querySelectorAll(".btnEditWorker");

    editWorkerButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const form = button.closest("form");
            const inputs = form.querySelectorAll(
                "input[name='worker_id'], input[name='first_name'], input[name='last_name'], input[name='email'], input[name='gender'], input[name='date_of_birth'], input[name='phone']"
            );
            const saveButton = form.querySelector(".btnSaveWorker");

            inputs.forEach((input) => {
                input.removeAttribute("disabled");
            });

            button.style.display = "none";
            saveButton.style.display = "inline-block";
        });
    });

    const saveWorkerButtons = document.querySelectorAll(".btnSaveWorker");

    saveWorkerButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const form = button.closest("form");
            const inputs = form.querySelectorAll(
                "input[name='worker_id'], input[name='first_name'], input[name='last_name'], input[name='email'], input[name='gender'], input[name='date_of_birth'], input[name='phone']"
            );
            const editButton = form.querySelector(".btnEditWorker");

            const data = {};
            inputs.forEach((input) => {
                const key = input.getAttribute("name");
                data[key] = input.value;
                input.setAttribute("disabled", "true");
            });

            editButton.style.display = "inline-block";
            button.style.display = "none";

            try {
                let response = await fetch("update_worker.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data),
                });

                if (response.ok) {
                    console.log("Worker successfully updated!");
                    // Show success message
                    let successPopup = document.querySelector("#successPopup");
                    successPopup.style.zIndex = "2000";
                    successPopup.classList.remove("hidden");
                    successPopup.style.display = "block";

                    setTimeout(function() {
                        successPopup.style.display = "none";
                        successPopup.classList.add("hidden");
                        successPopup.style.zIndex = "1000";
                    }, 2000); // 2 second delay in MILLISECONDS
                }
            } catch (error) {
                console.log(error);
            }
        });
    });

    // Delete worker
    const deleteWorkerButtons = document.querySelectorAll(".btnDeleteWorker");

    deleteWorkerButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            let form = button.closest("form");
            let workerId = form.querySelector("input[name='worker_id']").value;

            try {
                let response = await fetch("delete_worker.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ worker_id: workerId }),
                });

                if (response.ok) {
                    form.closest(".col-md-4").style.display = "none"; // Hide the worker container
                    // Show success message
                    let deletePopup = document.querySelector("#deletePopup");
                    deletePopup.style.zIndex = "2000";
                    deletePopup.classList.remove("hidden");
                    deletePopup.style.display = "block";

                    setTimeout(function() {
                        deletePopup.style.display = "none";
                        deletePopup.classList.add("hidden");
                        deletePopup.style.zIndex = "1000";
                    }, 2000); // 2 second delay in MILLISECONDS
                }
            } catch (error) {
                console.log(error);
            }
        });
    });

    //Add worker
    const addWorkerButton = document.querySelector(".btnAddWorker");
    const addWorkerForm = document.getElementById("addWorkerForm");

    addWorkerButton.addEventListener("click", () => {
        addWorkerForm.style.display = "block"; // Show the form
        addWorkerForm.reset();
        addWorkerButton.style.display = "none";
    });

    addWorkerForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const data = {};
        const inputs = addWorkerForm.querySelectorAll("input[name='workerId'], input[name='first_name'], input[name='last_name'], input[name='email'], select[name='gender'], input[name='date_of_birth'], input[name='phone'], input[name='password']");
        inputs.forEach((input) => {
           const key = input.getAttribute("name");
            data[key] = input.value;
        });

        try {
            console.log(data);
            const response = await fetch("add_worker.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                addWorkerButton.style.display = "block";
                addWorkerForm.reset();
                addWorkerForm.style.display = "none"; // Hide the form again
            }
        } catch (error) {
            console.log(error)
        }
    });

    // Edit user
    const editUserButtons = document.querySelectorAll(".btnEditUser");

    editUserButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const form = button.closest("form");
            const inputs = form.querySelectorAll(
                "input[name='user_id'], input[name='first_name'], input[name='last_name'], input[name='email'], input[name='gender'], input[name='date_of_birth'], input[name='phone']"
            );
            const saveButton = form.querySelector(".btnSaveUser");

            inputs.forEach((input) => {
                input.removeAttribute("disabled");
            });

            button.style.display = "none";
            saveButton.style.display = "inline-block";
        });
    });

    const saveUserButtons = document.querySelectorAll(".btnSaveUser");

    saveUserButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const form = button.closest("form");
            const inputs = form.querySelectorAll(
                "input[name='user_id'], input[name='first_name'], input[name='last_name'], input[name='email'], input[name='gender'], input[name='date_of_birth'], input[name='phone']"
            );
            const editButton = form.querySelector(".btnEditUser");

            const data = {};
            inputs.forEach((input) => {
                const key = input.getAttribute("name");
                data[key] = input.value;
                input.setAttribute("disabled", "true");
            });

            editButton.style.display = "inline-block";
            button.style.display = "none";

            try {
                let response = await fetch("update_worker.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data),
                });

                if (response.ok) {
                    console.log("User successfully updated!");
                    // Show success message
                    let successPopup = document.querySelector("#successPopup");
                    successPopup.style.zIndex = "2000";
                    successPopup.classList.remove("hidden");
                    successPopup.style.display = "block";

                    setTimeout(function() {
                        successPopup.style.display = "none";
                        successPopup.classList.add("hidden");
                        successPopup.style.zIndex = "1000";
                    }, 2000); // 2 second delay in MILLISECONDS
                }
            } catch (error) {
                console.log(error);
            }
        });
    });

    // Delete user
    const deleteUserButtons = document.querySelectorAll(".btnDeleteUser");

    deleteUserButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            let form = button.closest("form");
            let userId = form.querySelector("input[name='user_id']").value; // Assuming you've adjusted the HTML structure

            try {
                let response = await fetch("delete_worker.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ user_id: userId }),
                });

                if (response.ok) {
                    form.closest(".col-md-4").style.display = "none"; // Hide the worker container
                    // Show success message
                    let deletePopup = document.querySelector("#deletePopup");
                    deletePopup.style.zIndex = "2000";
                    deletePopup.classList.remove("hidden");
                    deletePopup.style.display = "block";

                    setTimeout(function() {
                        deletePopup.style.display = "none";
                        deletePopup.classList.add("hidden");
                        deletePopup.style.zIndex = "1000";
                    }, 2000); // 2 second delay in MILLISECONDS
                }
            } catch (error) {
                console.log(error);
            }
        });
    });

    // Add user
    const addUserButton = document.querySelector(".btnAddUser");
    const addUserForm = document.getElementById("addUserForm");

    addUserButton.addEventListener("click", () => {
        addUserForm.style.display = "block"; // Show the form
        addUserForm.reset();
        addUserButton.style.display = "none";
    });

    addUserForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const data = {};
        const inputs = addUserForm.querySelectorAll("input[name='first_name'], input[name='last_name'], input[name='email'], select[name='gender'], input[name='date_of_birth'], input[name='phone'], input[name='password']");
        inputs.forEach((input) => {
            const key = input.getAttribute("name");
            data[key] = input.value;
        });

        try {
            const response = await fetch("add_worker.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                addUserButton.style.display = "block";
                addUserForm.reset();
                addUserForm.style.display = "none"; // Hide the form again
            }
        } catch (error) {
            console.log(error);
        }
    });
});
