import { db } from "../Firebase/firebase_conn.js";
import { doc, getDoc } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

// ─── Import QR helper ────────────────────────────────────────────────────────
// getOrCreateQR:
//   • Checks Firestore users/{uid}.qrCodeURL first.
//   • If found → returns the stored Firebase Storage URL immediately (no API call).
//   • If missing → calls goqr.me, uploads the PNG to Firebase Storage,
//     writes the URL back to Firestore, then returns it.
// This means the QR is only ever generated ONCE per user and reused after that.
import { getOrCreateQR } from "../Firebase/qr_generator.js";

const uid = document.body.dataset.uid;

async function loadPortfolio() {
    const ref  = doc(db, "users", uid);
    const snap = await getDoc(ref);

    if (!snap.exists()) {
        document.body.textContent = "User not found";
        return;
    }

    const data = snap.data();

    if (!data.approved) {
        document.body.textContent = "Portfolio not approved";
        return;
    }

    // ── Populate profile UI ──────────────────────────────────────────────────
    document.getElementById("userName").textContent    = data.name || "Unknown";
    document.getElementById("userTitle").textContent   = data.professionalTitle || "Unknown";
    const companyEl = document.getElementById("userCompany");
    companyEl.innerHTML = ""; // clear safely

    const icon = document.createElement("i");
    icon.className = "fas fa-building";

    companyEl.append(icon, " ", data.businessName || "Unknown");

    document.getElementById("emailText").textContent    = data.email || "Unknown";
    document.getElementById("phoneText").textContent    = data.phone || "Unknown";
    document.getElementById("locationText").textContent = data.location || "Unknown";
    document.getElementById("addressText").textContent  = data.address || "Unknown";

    // Avatar initials — first letter of each word, max 2 chars
    const initials = data.name
        .split(" ")
        .map(n => n[0])
        .join("")
        .substring(0, 2)
        .toUpperCase();

    const avatarEl = document.getElementById("userAvatar");

    // Clear previous content
    avatarEl.innerHTML = "";

    if (data.logoUrl && data.logoUrl.trim() !== "") {
        // ✅ Show profile image
        const img = document.createElement("img");
        img.src = data.logoUrl;
        img.alt = "Profile Picture";

        img.style.width = "100%";
        img.style.height = "100%";
        img.style.objectFit = "cover";
        img.style.borderRadius = "6px";

        avatarEl.appendChild(img);
    } else {
        // ❌ Fallback to initials
        const initials = (data.name || "U")
            .split(" ")
            .map(n => n[0])
            .join("")
            .substring(0, 2)
            .toUpperCase();

        avatarEl.textContent = initials;
    }

    // ── QR Code: load from DB or auto-generate ───────────────────────────────
    // getOrCreateQR() handles the "first time" case transparently.
    // On subsequent visits the stored Firebase Storage URL is used directly.
    try {
        const qrURL = await getOrCreateQR(uid);
        const qrContainer = document.getElementById("qrCode");
        qrContainer.innerHTML = "";

        const img = document.createElement("img");
        img.src = qrURL;
        img.alt = "Portfolio QR Code";

        qrContainer.appendChild(img);
    } catch (err) {
        // Fallback: generate on-the-fly without saving (e.g. Storage write failed)
        console.warn("[check_portfolio] QR storage failed, using live fallback:", err);
        const fallbackURL =
            `https://api.qrserver.com/v1/create-qr-code/?size=150x150` +
            `&data=${encodeURIComponent(window.location.href)}`;
        document.getElementById("qrCode").innerHTML =
            `<img src="${fallbackURL}" alt="Portfolio QR Code">`;
    }
}

loadPortfolio();
function downloadCard() {
    const card     = document.getElementById("nfcCard");
    const userName = document.getElementById("userName");

    // FORCE browser paint
    userName.style.display = "none";
    userName.offsetHeight; // <-- forces reflow
    userName.style.display = "";

    card.classList.add("pdf-export");

    const options = {
        margin:       0,
        filename:     `${document.getElementById('userName').innerText}'s Portfolio.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  {
            scale: 2,          // Higher = sharper text
            scrollX: 0,
            scrollY: 0
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        }
    };

    html2pdf().set(options).from(card).save().then(() => {card.classList.remove("pdf-export");});;
}

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("downloadCard");
    if (btn) btn.addEventListener("click", downloadCard);
});