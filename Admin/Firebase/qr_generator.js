/**
 * qr_generator.js  (Firebase/qr_generator.js)
 * =============================================
 * Handles automatic QR code generation for each user's e-portfolio link.
 *
 * Flow:
 *   1. Build the portfolio URL:  https://domain.com/check_portfolio.php?uid=<uid>
 *   2. Call goqr.me API to get a QR code image (PNG, base64-encoded via fetch)
 *   3. Upload the PNG blob to Firebase Storage  →  firestore: users/{uid}.qrCodeURL
 *   4. Return the public download URL so the card can render it immediately
 *
 * Usage (from check_portfolio.php script block or e-portfolio.php):
 *
 *   import { generateAndSaveQR, getOrCreateQR } from "./Firebase/qr_generator.js";
 *
 *   // One-time generate + save:
 *   const url = await generateAndSaveQR(uid);
 *
 *   // Preferred: reads from DB first, only generates if missing:
 *   const url = await getOrCreateQR(uid);
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * NOTE:  PORTFOLIO_BASE_URL below must match your actual domain / path.
 *        Change it once here and it applies everywhere.
 * ─────────────────────────────────────────────────────────────────────────────
 */

import { db, storage } from "./firebase_conn.js";
import {
    doc,
    getDoc,
    updateDoc,
    serverTimestamp
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
import {
    ref,
    uploadBytes,
    getDownloadURL
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-storage.js";

// ─── CONFIG ──────────────────────────────────────────────────────────────────

/**
 * Base URL of the publicly accessible portfolio page.
 * Example: "https://cboc.com/check_portfolio.php"
 * Change this to your actual domain before deploying.
 */
const PORTFOLIO_BASE_URL = `https://cavitebusinessownersclub.infinityfree.me/smartdigital-admin-capstone/Admin/check_portfolio.php`;

/** QR image size in pixels (square). goqr.me supports up to 1000. */
const QR_SIZE = 300;

/** Firebase Storage folder that holds all QR images. */
const STORAGE_FOLDER = "qr_codes";

// ─────────────────────────────────────────────────────────────────────────────


// ─── HELPERS ─────────────────────────────────────────────────────────────────

/**
 * Builds the canonical portfolio URL for a given uid.
 * @param {string} uid
 * @returns {string}
 */
function buildPortfolioURL(uid) {
    return `${PORTFOLIO_BASE_URL}?uid=${encodeURIComponent(uid)}`;
}

/**
 * Fetches a QR code PNG from goqr.me as a Blob.
 * We use goqr.me because it:
 *  • requires no API key
 *  • supports CORS (returns image directly)
 *  • is free for reasonable usage
 *
 * @param {string} data  - The text / URL to encode
 * @returns {Promise<Blob>}
 */
async function fetchQRBlob(data) {
    const apiURL =
        `https://api.qrserver.com/v1/create-qr-code/` +
        `?size=${QR_SIZE}x${QR_SIZE}` +
        `&data=${encodeURIComponent(data)}` +
        `&format=png` +
        `&margin=10`;

    const response = await fetch(apiURL);
    if (!response.ok) {
        throw new Error(`QR API error: ${response.status} ${response.statusText}`);
    }
    return response.blob();  // PNG blob
}

// ─────────────────────────────────────────────────────────────────────────────


// ─── CORE EXPORTS ─────────────────────────────────────────────────────────────

/**
 * generateAndSaveQR
 * -----------------
 * Always generates a fresh QR code for the given uid, uploads it to
 * Firebase Storage, then writes the public URL back to Firestore
 * (users/{uid}.qrCodeURL).
 *
 * Call this when:
 *  • A user is first approved
 *  • An admin manually wants to regenerate the QR
 *
 * @param {string} uid  - Firestore user document ID
 * @returns {Promise<string>}  Public download URL of the stored QR image
 */
export async function generateAndSaveQR(uid) {
    const portfolioURL = buildPortfolioURL(uid);

    // 1. Fetch QR blob from goqr.me
    const blob = await fetchQRBlob(portfolioURL);

    // 2. Upload to Firebase Storage: qr_codes/{uid}.png
    const storageRef = ref(storage, `${STORAGE_FOLDER}/${uid}.png`);
    await uploadBytes(storageRef, blob, { contentType: "image/png" });

    // 3. Get the public download URL
    const downloadURL = await getDownloadURL(storageRef);

    // 4. Persist to Firestore so we don't regenerate on every page load
    await updateDoc(doc(db, "users", uid), {
        qrCodeURL:         downloadURL,
        qrCodeGeneratedAt: serverTimestamp()
    });

    console.log(`[qr_generator] QR saved for uid=${uid} → ${downloadURL}`);
    return downloadURL;
}


/**
 * getOrCreateQR
 * -------------
 * Reads the stored QR URL from Firestore.
 * If it doesn't exist yet (new user, or first approval), calls
 * generateAndSaveQR() automatically.
 *
 * This is the RECOMMENDED function to call on page load inside
 * check_portfolio.php — it avoids regenerating the QR every visit.
 *
 * @param {string} uid
 * @returns {Promise<string>}  Public QR image URL
 */
export async function getOrCreateQR(uid) {
    const userRef  = doc(db, "users", uid);
    const userSnap = await getDoc(userRef);

    if (!userSnap.exists()) {
        throw new Error(`[qr_generator] User ${uid} not found in Firestore.`);
    }

    const data = userSnap.data();

    // Already has a QR URL stored — return it immediately (no API call needed)
    if (data.qrCodeURL) {
        console.log(`[qr_generator] Using cached QR for uid=${uid}`);
        return data.qrCodeURL;
    }

    // First time (or QR was deleted) — generate, upload, save, return
    console.log(`[qr_generator] No cached QR found for uid=${uid}. Generating...`);
    return generateAndSaveQR(uid);
}