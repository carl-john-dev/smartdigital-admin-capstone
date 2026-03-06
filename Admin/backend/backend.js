/**
 * backend.js  (Admin/backend/backend.js)
 * ========================================
 * Centralized backend logic for the ENTIRE system.
 * All pages import from this one file.
 *
 * Usage from any page inside Admin/:
 *   import { fetchUsers, addUser, updateUser, deleteUser } from "./backend/backend.js";
 * 
 * ======================================================
 * WAG NA TAYO MAG INTERNAL DAHIL MASAKIT NA SA ULO GUYS 
 * ======================================================
 * 
 */

import { db } from "../Firebase/firebase_conn.js";
import {
    doc,
    collection,
    getDocs,
    addDoc,
    updateDoc,
    deleteDoc,
    serverTimestamp,
    onSnapshot
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";


// ─────────────────────────────────────────────
//  READ — Fetch all users/businesses (one-time)
// ─────────────────────────────────────────────

/**
 * Fetches all documents from the "businesses" collection once.
 * @returns {Promise<Array>} Array of user/business objects
 */
export async function fetchUsers() {
    try {
        const snapshot = await getDocs(collection(db, "businesses"));
        return snapshot.docs.map(doc => {
            const data = doc.data();
            return {
                id: doc.id,
                name: data.name || "",
                email: data.email || "",
                role: data.role || "Member",
                status: data.status || "offline",
                profilePic: data.profilePic || "",
                lastSeen: data.lastSeen
                    ? new Date(data.lastSeen.seconds * 1000).toLocaleString()
                    : "Never",
                avatar: data.avatar || null,
                address: data.address || "",
                coords: data.coords || []
            };
        });
    } catch (err) {
        console.error("fetchUsers error:", err);
        return [];
    }
}


// ─────────────────────────────────────────────
//  READ — Real-time listener (live updates)
// ─────────────────────────────────────────────

/**
 * Subscribes to real-time updates from the "businesses" collection.
 * Calls the provided callback whenever data changes.
 * 
 * @param {Function} callback - Called with an array of user objects on every update
 * @returns {Function} Unsubscribe function — call it to stop listening
 * 
 * Example:
 *   const unsubscribe = subscribeToUsers((users) => { ... });
 *   unsubscribe(); // stop listening
 */
export function subscribeToUsers(callback) {
    return onSnapshot(collection(db, "businesses"), (snapshot) => {
        const users = [];
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            if (!Array.isArray(data.coords) || data.coords.length !== 2) return;
            users.push({
                id: docSnap.id,
                name: data.name || "",
                email: data.email || "",
                role: data.role || "Member",
                status: data.status || "offline",
                profilePic: data.profilePic || "",
                lastSeen: data.lastSeen || "Never",
                avatar: data.avatar || null,
                address: data.address || "",
                coords: data.coords
            });
        });
        callback(users);
    }, (error) => {
        console.error("subscribeToUsers error:", error);
    });
}


// ─────────────────────────────────────────────
//  CREATE — Add a new user/business
// ─────────────────────────────────────────────

/**
 * Adds a new document to the "businesses" collection.
 * 
 * @param {Object} userData - The user data to save
 * @param {string} userData.name
 * @param {string} userData.email
 * @param {string} userData.role
 * @param {string} userData.status
 * @param {string} userData.address
 * @param {number[]} userData.coords  - [latitude, longitude]
 * @param {string} userData.avatar
 * @param {string} userData.profilePic
 * @returns {Promise<string>} The new document ID
 */
export async function addUser(userData) {
    try {
        const payload = {
            name: userData.name,
            email: userData.email,
            role: userData.role,
            status: userData.status,
            address: userData.address,
            coords: userData.coords,
            avatar: userData.avatar,
            profilePic: userData.profilePic,
            lastSeen: new Date().toISOString(),
            createdAt: serverTimestamp(),
            updatedAt: serverTimestamp()
        };

        const docRef = await addDoc(collection(db, "businesses"), payload);
        console.log("addUser: document created with ID:", docRef.id);
        return docRef.id;
    } catch (err) {
        console.error("addUser error:", err);
        throw err;
    }
}


// ─────────────────────────────────────────────
//  UPDATE — Edit an existing user/business
// ─────────────────────────────────────────────

/**
 * Updates an existing document in the "businesses" collection.
 * 
 * @param {string} userId - Firestore document ID
 * @param {Object} updatedData - Fields to update (partial update supported)
 * @returns {Promise<void>}
 */
export async function updateUser(userId, updatedData) {
    try {
        const payload = {
            ...updatedData,
            updatedAt: serverTimestamp()
        };
        await updateDoc(doc(db, "businesses", userId), payload);
        console.log("updateUser: document updated:", userId);
    } catch (err) {
        console.error("updateUser error:", err);
        throw err;
    }
}


// ─────────────────────────────────────────────
//  DELETE — Remove a user/business
// ─────────────────────────────────────────────

/**
 * Deletes a document from the "businesses" collection.
 * 
 * @param {string} userId - Firestore document ID to delete
 * @returns {Promise<void>}
 */
export async function deleteUser(userId) {
    try {
        await deleteDoc(doc(db, "businesses", userId));
        console.log("deleteUser: document deleted:", userId);
    } catch (err) {
        console.error("deleteUser error:", err);
        throw err;
    }
}


// ─────────────────────────────────────────────
//  UTILITY HELPERS  (shared across all pages)
// ─────────────────────────────────────────────

/**
 * Converts a full name to 2-letter initials.
 * @param {string} name
 * @returns {string} e.g. "John Doe" → "JD"
 */
export function getInitials(name) {
    return (name || "")
        .split(" ")
        .map(word => word[0])
        .join("")
        .toUpperCase()
        .substring(0, 2);
}

/**
 * Returns a random profile picture URL from randomuser.me.
 * Used as a fallback when no profile picture is provided.
 * @returns {string} Image URL
 */
export function getDefaultProfilePic() {
    const genders = ["men", "women"];
    const gender = genders[Math.floor(Math.random() * genders.length)];
    const number = Math.floor(Math.random() * 100);
    return `https://randomuser.me/api/portraits/${gender}/${number}.jpg`;
}

/**
 * Geocodes an address to coordinates using OpenStreetMap Nominatim.
 * Appends "Rosario, Cavite, Philippines" for local accuracy.
 * 
 * @param {string} address
 * @returns {Promise<{lat: number, lng: number}|null>}
 */
export async function geocodeAddress(address) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}, Rosario, Cavite, Philippines&limit=1`
        );
        const data = await response.json();

        if (data && data.length > 0) {
            return {
                lat: parseFloat(data[0].lat),
                lng: parseFloat(data[0].lon)
            };
        }
        throw new Error("Address not found");
    } catch (err) {
        console.error("geocodeAddress error:", err);
        return null;
    }
}