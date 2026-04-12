/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { app } from '../Firebase/firebase_conn.js';
import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-auth.js";
import { showToast } from './backend.js';

const auth = getAuth(app);
let loginAttempts = 0; 

document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const loginBtn = document.getElementById("loginBtn");
    const btnText = loginBtn.querySelector(".btn-text");
    const spinner = loginBtn.querySelector(".spinner");
    const MAX_ATTEMPTS = 5;

    if (loginAttempts >= MAX_ATTEMPTS) {
        showToast("Too many login attempts. Please wait.", "danger");
        return;
    }

    // Disable button & show spinner
    loginBtn.disabled = true;
    btnText.style.display = "none";
    spinner.style.display = "inline-block";

    const TEST_DOMAIN = "@cboc.test";
    const username = document
        .getElementById("login-username")
        .value
        .trim()
        .replace(/[^\w.@-]/g, "");
    const password = document.getElementById("login-password").value;
    const csrf = document.getElementById("csrf_token").value;

    // Map username → email (temporary testing logic)
    const email = username.includes("@") ? username : username + TEST_DOMAIN;
    
    try { 
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;
        const idToken = await user.getIdToken();

        // Send to PHP
        const response = await fetch("./verify_token.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Csrf-Token": csrf
            },
            credentials: "same-origin",
            body: JSON.stringify({ 
                token: idToken ,
    			csrf: csrf
            })
        });

        if (!response.ok) {
            showToast("Session setup failed", "warning");
            console.error(await response.text());
            // Re-enable button & hide spinner
            loginBtn.disabled = false;
            btnText.style.display = "inline";
            spinner.style.display = "none";
            return;
        }

        window.location.href = "dashboard.php";
        
    } catch (error) { 
        showToast("Invalid username or password.", "warning");
        console.error(error);
        loginAttempts++;
        // Re-enable button & hide spinner
        loginBtn.disabled = false;
        btnText.style.display = "inline";
        spinner.style.display = "none";
    } 
});