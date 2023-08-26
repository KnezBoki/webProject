function updatePasswordInputColor(isStrong) {
    const passwordInput = document.getElementById("password");

    if (isStrong) {
        passwordInput.classList.remove("password-weak");
        passwordInput.classList.add("password-strong");
    } else {
        passwordInput.classList.remove("password-strong");
        passwordInput.classList.add("password-weak");
    }
}

function checkPasswordStrength(passwordInput) {
    let password = passwordInput.value;

    fetch("password_strength.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ password: password })
    })
        .then(response => response.json())
        .then(data => {
            updatePasswordInputColor(data.complexity === "Strong");
        })
        .catch(error => {
            console.error("Error checking password strength:", error);
            updatePasswordInputColor(false);
        });
}
