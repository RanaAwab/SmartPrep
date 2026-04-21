// ===============================
// GLOBAL FUNCTIONS
// ===============================

// Confirm delete
function confirmDelete(message = "Are you sure?") {
    return confirm(message);
}

// ===============================
// AUTO HIDE ALERTS
// ===============================

document.addEventListener("DOMContentLoaded", function () {

    setTimeout(() => {
        let alerts = document.querySelectorAll(".alert");
        alerts.forEach(alert => {
            alert.style.transition = "0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);

});

// ===============================
// FORM VALIDATION (BASIC)
// ===============================

function validateRequired(form) {
    let inputs = form.querySelectorAll("[required]");
    let valid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.border = "1px solid red";
            valid = false;
        } else {
            input.style.border = "1px solid #ccc";
        }
    });

    return valid;
}

// ===============================
// PASSWORD TOGGLE
// ===============================

function togglePassword(id) {
    let field = document.getElementById(id);

    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}

// ===============================
// LIVE SEARCH (TABLE)
// ===============================

function searchTable(inputId, tableId) {
    let input = document.getElementById(inputId);
    let filter = input.value.toLowerCase();
    let rows = document.querySelectorAll(`#${tableId} tbody tr`);

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
}

// ===============================
// LOADING BUTTON
// ===============================

function showLoading(btn) {
    btn.disabled = true;
    btn.innerHTML = "Loading...";
}