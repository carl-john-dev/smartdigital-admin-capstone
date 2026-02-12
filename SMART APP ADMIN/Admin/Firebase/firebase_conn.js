import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
import { getStorage } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-storage.js";

const firebaseConfig = {
    apiKey: "AIzaSyAQLWlfqnxqNCoHxTh6RL0ZSXZeJ7legz0",
    authDomain: "smartcard-475413.firebaseapp.com",
    projectId: "smartcard-475413",
    storageBucket: "smartcard-475413.firebasestorage.app",
    messagingSenderId: "731351689459",
    appId: "1:731351689459:web:1331697726d9aab1092f86",
    measurementId: "G-LHS2EQ9Z5F"
};

const app = initializeApp(firebaseConfig);
export const db = getFirestore(app);
export const storage = getStorage(app);