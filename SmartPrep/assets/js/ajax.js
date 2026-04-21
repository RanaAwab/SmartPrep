// ===============================
// BASE API HELPER
// ===============================

async function apiRequest(url, method = "GET", data = null) {
    try {
        let options = {
            method: method,
            headers: {
                "Content-Type": "application/json"
            }
        };

        if (data) {
            options.body = JSON.stringify(data);
        }

        let response = await fetch(url, options);
        return await response.json();

    } catch (error) {
        console.error("API Error:", error);
        return { status: "error", message: "Something went wrong" };
    }
}

// ===============================
// LOGIN VIA AJAX
// ===============================

async function loginUser(event) {
    event.preventDefault();

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    let res = await apiRequest("api/login.php", "POST", {
        email,
        password
    });

    if (res.status === "success") {
        window.location.href = res.redirect;
    } else {
        alert(res.message);
    }
}

// ===============================
// REGISTER VIA AJAX
// ===============================

async function registerUser(event) {
    event.preventDefault();

    let data = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        role: document.getElementById("role").value
    };

    let res = await apiRequest("api/register.php", "POST", data);

    alert(res.message);
}

// ===============================
// FETCH COURSES (EXAMPLE)
// ===============================

async function loadCourses() {
    let res = await apiRequest("api/get_courses.php");

    let container = document.getElementById("courseList");

    if (res.status === "success") {
        container.innerHTML = "";

        res.data.forEach(course => {
            container.innerHTML += `
                <div class="card p-2 mb-2">
                    ${course.name} (${course.code})
                </div>
            `;
        });
    }
}

// ===============================
// SUBMIT ASSIGNMENT AJAX
// ===============================

async function submitAssignment(event) {
    event.preventDefault();

    let data = {
        assignment_id: document.getElementById("assignment_id").value,
        content: document.getElementById("content").value
    };

    let res = await apiRequest("api/submit_assignment.php", "POST", data);

    alert(res.message);
}