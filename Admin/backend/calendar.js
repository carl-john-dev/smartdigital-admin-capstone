/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { db, storage } from '../Firebase/firebase_conn.js';
import { 
    collection, 
    query, 
    where, 
    doc, 
    getDocs, 
    getDoc, 
    addDoc, 
    updateDoc, 
    deleteDoc, 
    serverTimestamp, 
    Timestamp, 
    onSnapshot, 
    or 
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
import { ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-storage.js";

async function testFirestore() {
    try {
        const q = query(collection(db, "events"), where("createdBy", "==", "Admin"));
        const snapshot = await getDocs(q);
        console.log("Number of documents in 'events':", snapshot.size);
        // snapshot.forEach(docSnap => {
        //     console.log(docSnap.id, docSnap.data());
        // });
    } catch (error) {
        console.error("Firestore error:", error);
    }
}
testFirestore();

// Three Dots Menu Functions
function exportEvents() {
    if (events.length === 0) {
        alert('No events to export');
        return;
    }
    
    const dataStr = JSON.stringify(events, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    const exportFileDefaultName = 'cboc-events-export.json';
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
    
    showNotification('Events exported successfully!', 'success');
};

function printCalendar() {
    window.print();
};

function refreshCalendar() {
    location.reload();
};

function showCalendarHelp() {
    alert(`
Calendar Help:
- Click "Create New Event" to add events
- Click on event cards to view details
- Use Previous/Next buttons to navigate months
- Events are color-coded by category
- Toggle dark mode using the moon/sun button
    `);
};

document.addEventListener('DOMContentLoaded', function() {
    validateAndSetDateInput();
    // Three Dots Menu Toggle
    const dotsMenuBtn = document.getElementById('dotsMenuBtn');
    const dotsDropdown = document.getElementById('dotsDropdown');

    dotsMenuBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dotsDropdown.classList.toggle('show');
    });

    document.addEventListener('click', function() {
        dotsDropdown.classList.remove('show');
    });

    // Initialize Quill Rich Text Editor
    const quill = new Quill('#richTextEditor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        },
        placeholder: 'Write event description here...'
    });

    // Calendar functionality
    let currentDate = new Date();
    let events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
    let eventToDelete = null;
    let eventToEdit = null;
    let selectedImages = [];
    let editEventsFlag = false;
    
    // Philippine Time Clock Functionality
    function updatePhilippineTime() {
        const phDateElement = document.getElementById('phDate');
        const phTimeElement = document.getElementById('phTime');
        
        // Philippine Time is UTC+8
        const now = new Date();
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        const phTime = new Date(utc + (3600000 * 8)); // UTC+8
        
        // Format date (e.g., "January 15, 2024")
        const optionsDate = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const formattedDate = phTime.toLocaleDateString('en-US', optionsDate);
        
        // Format time (e.g., "14:30:45")
        const hours = phTime.getHours().toString().padStart(2, '0');
        const minutes = phTime.getMinutes().toString().padStart(2, '0');
        const seconds = phTime.getSeconds().toString().padStart(2, '0');
        const formattedTime = `${hours}:${minutes}:${seconds}`;
        
        phDateElement.textContent = formattedDate;
        phTimeElement.textContent = formattedTime;
    }

    // Upload images to Firebase
    async function uploadImages(files) {
        const urls = [];

        for (const file of files) {
            const storageRef = ref(
            storage,
            `events/${Date.now()}_${file.name}`
            );

            await uploadBytes(storageRef, file);
            const url = await getDownloadURL(storageRef);
            urls.push(url);
        }

        return urls;
    }

    // Create data on Firebase DB
    document.getElementById("saveEvent").addEventListener("click", async () => {
        try {
            // Basic fields
            const title = document.getElementById("eventTitle").value.trim();
            const category = document.getElementById("eventCategory").value;
            const date = document.getElementById("eventDate").value;
            const venue = document.getElementById("eventVenue").value.trim();
            const description = quill.root.innerHTML;
            const createdBy = "Admin";

            // Time parsing
            const startTime = document.getElementById("startTime").value;
            const endTime = document.getElementById("endTime").value;

            let startHour = null, startMinute = null;
            let endHour = null, endMinute = null;

            if (startTime) [startHour, startMinute] = startTime.split(":").map(Number);
            if (endTime) [endHour, endMinute] = endTime.split(":").map(Number);

            // Switches
            const pub_to_cal = document.getElementById("publishEvent").checked;
            const send_notif = document.getElementById("sendNotification").checked;

            // Images
            const imageFiles = document.getElementById("eventImages").files;
            const imageUrl = imageFiles.length
                ? await uploadImages(imageFiles)
                : eventToEdit?.imageUrl || [];

            const payload = {
                title,
                category,
                date,
                startHour,
                startMinute,
                endHour,
                endMinute,
                venue,
                imageUrl,
                description,
                pub_to_cal,
                send_notif,
                createdBy,
                updatedAt: serverTimestamp()
            };

            if (editEventsFlag && eventToEdit?.id) {
                // 🔁 UPDATE
                await updateDoc(doc(db, "events", eventToEdit.id), payload);
                alert("Event updated successfully!");
            } else {
                // ➕ CREATE
                await addDoc(collection(db, "events"), {
                    ...payload,
                    createdAt: serverTimestamp()
                });
                alert("Event created successfully!");
            }

            // Reset state
            editEventsFlag = false;
            eventToEdit = null;
            document.getElementById("eventForm").reset();
            quill.root.innerHTML = "";

        } catch (error) {
            console.error("Error saving event:", error);
            alert("Failed to save event.");
        }
    });

    // Initialize and update clock every second
    updatePhilippineTime();
    setInterval(updatePhilippineTime, 1000);
    
    // Initialize calendar
    renderCalendar(currentDate);
    renderPublishedEvents();
    updateStatistics();
    
    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const body = document.body;
    
    // Check for saved dark mode preference
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
    
    // Apply dark mode if previously enabled
    if (isDarkMode) {
        body.classList.add('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
    }
    
    // Toggle dark mode
    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        // Update icon
        if (body.classList.contains('dark-mode')) {
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            darkModeIcon.classList.remove('fa-sun');
            darkModeIcon.classList.add('fa-moon');
            localStorage.setItem('darkMode', 'disabled');
        }
    });
    
    // Calendar navigation
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });
    
    document.getElementById('nextMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });
    
    // Handle image upload preview
    document.getElementById('eventImages').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('imagePreview');
        previewContainer.innerHTML = '';
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageData = e.target.result;
                    selectedImages.push(imageData);
                    
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview';
                    previewDiv.innerHTML = `
                        <img src="${imageData}" alt="Preview ${i + 1}">
                        <button type="button" class="remove-image" data-index="${selectedImages.length - 1}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            }
        }
    });
    
    // Clear images button
    document.getElementById('clearImages').addEventListener('click', function() {
        document.getElementById('eventImages').value = '';
        document.getElementById('imagePreview').innerHTML = '';
        selectedImages = [];
    });
    
    // Remove image from preview
    document.getElementById('imagePreview').addEventListener('click', function(e) {
        if (e.target.closest('.remove-image')) {
            const index = parseInt(e.target.closest('.remove-image').dataset.index);
            selectedImages.splice(index, 1);
            renderImagePreviews();
        }
    });
    
    // Save event as draft
    document.getElementById('saveDraft').addEventListener('click', function() {
        saveEvent(false);
    });
    
    // Save and publish event
    document.getElementById('saveEvent').addEventListener('click', function() {
        saveEvent(true);
    });
    
    // Delete event button
    document.getElementById('deleteEventBtn').addEventListener('click', function() {
        const eventDetailsModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal'));
        eventDetailsModal.hide();
        
        // Show delete confirmation modal
        const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteConfirmModal.show();
    });
    
    // Edit event button
    document.getElementById('editEventBtn').addEventListener('click', function() {
        const eventDetailsModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal'));
        eventDetailsModal.hide();
        
        if (eventToEdit?.id) {
            editEvent(eventToEdit.id);
        }
    });
    
    // Confirm delete button
    document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
        if (!eventToDelete?.id) {
            alert("No event selected for deletion.");
            return;
        }

        try {
            // Delete document from Firestore
            await deleteDoc(doc(db, "events", eventToDelete.id));

            // Close confirmation modal
            bootstrap.Modal
                .getInstance(document.getElementById('deleteConfirmModal'))
                .hide();

            // Reset state
            eventToDelete = null;

            // Refresh UI from Firestore (single source of truth)
            await renderPublishedEvents();
            updateStatistics();

            alert("Event deleted successfully.");

        } catch (error) {
            console.error("Failed to delete event:", error);
            alert("Failed to delete event.");
        }
    });
    
    // Set today's date as default in the form
    document.getElementById('eventDate').valueAsDate = new Date();

    const toggleBtn = document.getElementById("toggleEventsBtn");
    const wrapper = document.getElementById("publishedEventsWrapper");

    toggleBtn.addEventListener("click", () => {
        if (wrapper.classList.contains("events-collapsed")) {
            wrapper.classList.remove("events-collapsed");
            wrapper.classList.add("events-expanded");
            toggleBtn.innerHTML = '<i class="fas fa-compress"></i> Collapse';
        } else {
            wrapper.classList.remove("events-expanded");
            wrapper.classList.add("events-collapsed");
            toggleBtn.innerHTML = '<i class="fas fa-expand"></i> Expand';
        }
    });
    
    // Calendar rendering function
    async function renderCalendar(date) {
        const calendarGrid = document.getElementById('calendarGrid');
        const currentMonthYear = document.getElementById('currentMonthYear');

        // Clear previous calendar
        while (calendarGrid.children.length > 7) calendarGrid.removeChild(calendarGrid.lastChild);

        // Set month/year
        const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        currentMonthYear.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;

        // Compute first/last days
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();

        // Fetch all events for the month at once
        const monthStart = `${date.getFullYear()}-${(date.getMonth()+1).toString().padStart(2,'0')}-01`;
        const monthEnd = `${date.getFullYear()}-${(date.getMonth()+1).toString().padStart(2,'0')}-${daysInMonth.toString().padStart(2,'0')}`;
        
        const eventsRef = collection(db, 'events');
        const approvedQuery = query(eventsRef, where('approved','==',true));
        const adminQuery = query(eventsRef, where('createdBy','==','Admin'));
        const [approvedSnap, adminSnap] = await Promise.all([getDocs(approvedQuery), getDocs(adminQuery)]);
        const allDocs = [...approvedSnap.docs, ...adminSnap.docs];

        // Map events by date string for quick lookup
        const eventsByDate = {};
        allDocs.forEach(doc => {
            const data = doc.data();
            let eventDate;
            if (data.date instanceof Timestamp) {
                const ts = data.date.toDate();
                eventDate = `${ts.getFullYear()}-${(ts.getMonth()+1).toString().padStart(2,'0')}-${ts.getDate().toString().padStart(2,'0')}`;
            } else {
                eventDate = data.date;
            }

            if (!eventsByDate[eventDate]) eventsByDate[eventDate] = [];
            eventsByDate[eventDate].push({
                id: doc.id,
                ...data
            });
        });

        // Helper function to create day element synchronously
        function createDayElementSync(dayNumber, isOtherMonth) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            if (isOtherMonth) dayElement.classList.add('other-month');

            const dayNumberElement = document.createElement('div');
            dayNumberElement.className = 'day-number';
            dayNumberElement.textContent = dayNumber;
            dayElement.appendChild(dayNumberElement);

            const dayStr = `${date.getFullYear()}-${(date.getMonth()+1).toString().padStart(2,'0')}-${dayNumber.toString().padStart(2,'0')}`;
            const dayEvents = eventsByDate[dayStr] || [];

            if (dayEvents.length > 0) {
                dayElement.classList.add('has-events');
                dayEvents.forEach(eventData => {
                    const eventElement = document.createElement('div');
                    eventElement.className = `event-item ${eventData.category || ''}`;
                    eventElement.textContent = eventData.title || '';
                    eventElement.setAttribute('data-bs-toggle','tooltip');
                    eventElement.setAttribute('title',`${eventData.title}${eventData.startTime ? ' - '+eventData.startTime : ''}`);
                    eventElement.addEventListener('click', e => {
                        e.stopPropagation();
                        showEventDetails(eventData);
                    });
                    dayElement.appendChild(eventElement);
                });
            }
            return dayElement;
        }

        // Previous month tail
        const prevMonthLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
        for (let i = startingDay-1; i >= 0; i--) {
            calendarGrid.appendChild(createDayElementSync(prevMonthLastDay - i, true));
        }

        // Current month
        for (let i = 1; i <= daysInMonth; i++) {
            calendarGrid.appendChild(createDayElementSync(i, false));
        }

        // Next month fill
        const totalCells = 42;
        const daysSoFar = startingDay + daysInMonth;
        const nextMonthDays = totalCells - daysSoFar;
        for (let i = 1; i <= nextMonthDays; i++) {
            calendarGrid.appendChild(createDayElementSync(i, true));
        }
    }
    
    // Create a day element
    const checkDate = new Date(year, month, dayNumber);
    checkDate.setHours(0,0,0,0);
    const today = new Date();
    today.setHours(0,0,0,0);
    const isPast = checkDate < today;

    if(isPast && !isOtherMonth) {
        dayDiv.classList.add('past-date');
    }

    async function createDayElement(dayNumber, isOtherMonth, currentDate) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';

        if (isOtherMonth) dayElement.classList.add('other-month');

        const dayNumberElement = document.createElement('div');
        dayNumberElement.className = 'day-number';
        dayNumberElement.textContent = dayNumber;
        dayElement.appendChild(dayNumberElement);

        // Format YYYY-MM-DD
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        const dateString = `${year}-${month.toString().padStart(2,'0')}-${dayNumber.toString().padStart(2,'0')}`;

        try {
            const eventsRef = collection(db, 'events');

            // Query approved events OR admin-created events
            const approvedQuery = query(eventsRef, where('approved', '==', true));
            const adminQuery = query(eventsRef, where('createdBy', '==', 'Admin'));

            const [approvedSnap, adminSnap] = await Promise.all([
                getDocs(approvedQuery),
                getDocs(adminQuery)
            ]);

            const allDocs = [...approvedSnap.docs, ...adminSnap.docs];

            // Filter for this date
            const dayEvents = allDocs.filter(doc => {
                const data = doc.data();
                let eventDate;

                if (data.date instanceof Timestamp) {
                    const ts = data.date.toDate();
                    eventDate = `${ts.getFullYear()}-${(ts.getMonth()+1).toString().padStart(2,'0')}-${ts.getDate().toString().padStart(2,'0')}`;
                } else {
                    eventDate = data.date; // assume YYYY-MM-DD string
                }

                return eventDate === dateString;
            });

            if (dayEvents.length > 0) {
                dayElement.classList.add('has-events');

                dayEvents.forEach(doc => {
                    const data = doc.data();
                    const eventElement = document.createElement('div');
                    eventElement.className = `event-item ${data.category || ''}`;
                    eventElement.textContent = data.title || '';
                    eventElement.setAttribute('data-bs-toggle', 'tooltip');
                    eventElement.setAttribute('title', `${data.title}${data.startTime ? ' - ' + data.startTime : ''}`);
                    eventElement.addEventListener('click', e => {
                        e.stopPropagation();
                        showEventDetails(data);
                    });
                    dayElement.appendChild(eventElement);
                });
            }

        } catch (err) {
            console.error('Error fetching events:', err);
        }

        return dayElement;
    }
    
    // Render published events
    async function renderPublishedEvents() {
        const container = document.getElementById('publishedEvents');
        container.innerHTML = ''; // OK: clearing only

        try {
            const q = query(
                collection(db, "events"), 
                or(
                    where("createdBy", "==", "Admin"),
                    where("approved", "==", true)
                )
            );

            const querySnapshot = await getDocs(q);

            const publishedEvents = [];
            querySnapshot.forEach(docSnap => {
                const data = docSnap.data();
                publishedEvents.push({
                    id: docSnap.id,
                    ...data
                });
            });

            publishedEvents.sort((a, b) => new Date(a.date) - new Date(b.date));

            if (publishedEvents.length === 0) {
                const wrapper = document.createElement('div');
                wrapper.className = 'text-center py-5';

                const icon = document.createElement('i');
                icon.className = 'fas fa-calendar-plus fa-3x text-muted mb-3';

                const text = document.createElement('p');
                text.className = 'text-muted';
                text.textContent = 'No published events yet';

                const btn = document.createElement('button');
                btn.className = 'btn btn-primary';
                btn.setAttribute('data-bs-toggle', 'modal');
                btn.setAttribute('data-bs-target', '#eventModal');
                btn.innerHTML = `<i class="fas fa-plus me-2"></i> Create First Event`;

                wrapper.append(icon, text, btn);
                container.appendChild(wrapper);
                return;
            }

            const today = new Date();
            today.setHours(0,0,0,0);

            publishedEvents.forEach(event => {
                let eventDate;

                if (event.date?.toDate) {
                    eventDate = event.date.toDate();
                } else {
                    eventDate = new Date(event.date);
                }

                if (eventDate < today) return;

                const card = document.createElement('div');
                card.className = 'event-card';

                // IMAGE
                if (event.imageUrl) {
                    const img = document.createElement('img');
                    img.src = event.imageUrl;
                    img.className = 'event-card-image';
                    img.alt = event.title || 'Event image';
                    card.appendChild(img);
                }

                // TITLE
                const title = document.createElement('h4');
                title.className = 'event-card-title';
                title.textContent = event.title || 'Untitled Event';

                // META
                const meta = document.createElement('div');
                meta.className = 'event-card-meta';

                const dateSpan = document.createElement('span');
                dateSpan.className = 'event-meta-item';
                dateSpan.textContent = eventDate.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                meta.appendChild(dateSpan);

                if (event.startTime) {
                    const timeSpan = document.createElement('span');
                    timeSpan.className = 'event-meta-item';
                    timeSpan.textContent = `${event.startTime}${event.endTime ? ' - ' + event.endTime : ''}`;
                    meta.appendChild(timeSpan);
                }

                if (event.venue) {
                    const venueSpan = document.createElement('span');
                    venueSpan.className = 'event-meta-item';
                    venueSpan.textContent = event.venue;
                    meta.appendChild(venueSpan);
                }

                // DESCRIPTION (SAFE)
                const desc = document.createElement('div');
                desc.className = 'event-card-description';
                desc.textContent = event.description
                    ? event.description.replace(/<[^>]*>?/gm, '').substring(0, 200)
                    : 'No description';

                // FOOTER
                const footer = document.createElement('div');
                footer.className = 'event-card-footer';

                const actions = document.createElement('div');
                actions.className = 'event-actions';

                const viewBtn = document.createElement('button');
                viewBtn.className = 'btn btn-sm btn-outline-primary';
                viewBtn.textContent = 'View';
                viewBtn.addEventListener('click', () => {
                    showEventDetails(event);
                });

                const editBtn = document.createElement('button');
                editBtn.className = 'btn btn-sm btn-outline-warning';
                editBtn.textContent = 'Edit';
                editBtn.addEventListener('click', () => {
                    editEvent(event.id);
                });

                actions.append(viewBtn, editBtn);
                footer.appendChild(actions);

                // BUILD CARD
                card.append(title, meta, desc, footer);
                container.appendChild(card);
            });

        } catch (error) {
            console.error("Error fetching events:", error);
            container.textContent = "Failed to load events.";
        }
    }
    
    const selectedDate = document.getElementById('eventDate').value;
    if(!isDateAllowed(selectedDate)) {
        alert("Cannot create event for past dates or dates beyond 5 months!");
        return;
    }
    // Save event function
    function saveEvent(publish) {
        const title = document.getElementById('eventTitle').value;
        const date = document.getElementById('eventDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;
        const category = document.getElementById('eventCategory').value;
        const venue = document.getElementById('eventVenue').value;
        const description = quill.root.innerHTML;
        const sendNotification = document.getElementById('sendNotification').checked;
        
        if (title && date) {
            const event = {
                id: eventToEdit ? eventToEdit.id : Date.now(),
                title,
                date,
                startTime,
                endTime,
                category,
                venue,
                description,
                images: [...selectedImages],
                published: publish,
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            };
            
            if (eventToEdit) {
                // Update existing event
                const index = events.findIndex(e => e.id === eventToEdit.id);
                if (index !== -1) {
                    events[index] = event;
                }
            } else {
                // Add new event
                events.push(event);
            }
            
            // Save to localStorage
            localStorage.setItem('calendarEvents', JSON.stringify(events));
            
            // Reset form and close modal
            resetForm();
            bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
            
            // Show success message
            showNotification(`Event "${title}" ${publish ? 'published' : 'saved as draft'} successfully!`, 'success');
            
            // Send notification if enabled
            if (publish && sendNotification) {
                simulateNotification(event);
            }
            
            // Re-render calendar and events
            renderCalendar(currentDate);
            renderPublishedEvents();
            updateStatistics();
            
            // Reset editing state
            eventToEdit = null;
            selectedImages = [];
        }
    }
    
    // Edit event function
    async function editEvent(eventId) {
        if (typeof eventId !== "string") {
            console.error("Invalid eventId:", eventId);
            return;
        }

        try {
            // Fetch event directly from Firestore
            const eventRef = doc(db, "events", eventId);
            const eventSnap = await getDoc(eventRef);

            if (!eventSnap.exists()) {
                alert("Event not found.");
                return;
            }

            const event = {
                id: eventSnap.id,
                ...eventSnap.data()
            };

            eventToEdit = event; // keep reference for update/save later

            // Populate form fields
            document.getElementById('eventTitle').value = event.title || '';
            document.getElementById('eventDate').value = event.date || '';
            document.getElementById('startTime').value = event.startTime || '';
            document.getElementById('endTime').value = event.endTime || '';
            document.getElementById('eventCategory').value = event.category || '';
            document.getElementById('eventVenue').value = event.venue || '';
            document.getElementById('publishEvent').checked = event.published === true;

            // Set rich text editor content
            quill.root.innerHTML = event.description || '';

            // Images (Firestore-safe)
            selectedImages = event.images || [];
            renderImagePreviews();

            // Update modal title
            document.getElementById('eventModalLabel').textContent = 'Edit Event';

            // Show modal
            const eventModal = new bootstrap.Modal(
                document.getElementById('eventModal')
            );
            eventModal.show();
            
            editEventsFlag = true;

        } catch (error) {
            console.error("Failed to load event from Firestore:", error);
            alert("Failed to load event data.");
        }
    }

    function formatEventDate(value) {
        if (!value) return "N/A";

        let date;

        // Firestore Timestamp
        if (value?.toDate) {
            date = value.toDate();
        }
        // Timestamp object with seconds
        else if (value?.seconds) {
            date = new Date(value.seconds * 1000);
        }
        // String or number
        else {
            date = new Date(value);
        }

        if (isNaN(date)) return "Invalid Date";

        return date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    // Show event details
    function showEventDetails(event) {
        eventToDelete = event;
        eventToEdit = event;
        
        let eventDate;
        // Handle Firestore Timestamp
        if (event.date && typeof event.date.toDate === "function") {
            eventDate = event.date.toDate();
        }
        // Handle YYYY-MM-DD string
        else if (typeof event.date === "string") {
            eventDate = new Date(event.date);
        }
        const formattedDate = eventDate.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        let timeInfo = '';
        if (event.startTime && event.endTime) {
            timeInfo = `<p><strong>Time:</strong> ${event.startTime} - ${event.endTime}</p>`;
        } else if (event.startTime) {
            timeInfo = `<p><strong>Time:</strong> ${event.startTime}</p>`;
        }
        
        let venueInfo = event.venue ? `<p><strong>Venue:</strong> ${event.venue}</p>` : '';
        
        let imagesHtml = '';
        if (event.images && event.images.length > 0) {
            imagesHtml = `
                <div class="mb-3">
                    <strong>Event Images:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        ${event.images.map((img, index) => `
                            <img src="${img}" class="event-details-image" alt="Event Image ${index + 1}" style="max-width: 150px; max-height: 100px; object-fit: cover;">
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        const eventDetailsContent = document.getElementById('eventDetailsContent');
        eventDetailsContent.innerHTML = `
            <div class="text-center mb-3">
                <span class="badge bg-${getCategoryColor(event.category)} mb-2">${event.category || "Uncaregorized"}</span>
                <h4>${event.title}</h4>
            </div>
            ${imagesHtml}
            <div class="mb-3">
                <p><strong>Date:</strong> ${formattedDate}</p>
                ${timeInfo}
                ${venueInfo}
                ${event.description ? `<div class="mt-3">
                    <strong>Description:</strong>
                    <div class="border rounded p-3 mt-2">${event.description}</div>
                </div>` : ''}
            </div>
            <div class="text-muted small">
                <p><strong>Status:</strong> ${event.published ? 'Published' : 'Draft'}</p>
                <p><strong>Created:</strong> ${formatEventDate(event.createdAt)}</p>
                ${event.updatedAt ? `<p><strong>Last Updated:</strong> ${formatEventDate(event.updatedAt)}</p>` : ''}
            </div>
        `;
        
        // Show the event details modal
        const eventDetailsModal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        eventDetailsModal.show();
    }
    
    // Helper function to get category color
    function getCategoryColor(category) {
        switch(category) {
            case 'meeting': return 'primary';
            case 'deadline': return 'danger';
            case 'event': return 'purple';
            case 'training': return 'success';
            case 'reminder': return 'warning';
            default: return 'secondary';
        }
    }
    
    // Update statistics
    function updateStatistics() {
        const totalEvents = events.length;
        const publishedCount = events.filter(event => event.published).length;
        
        document.getElementById('totalEvents').textContent = totalEvents;
        document.getElementById('publishedCount').textContent = publishedCount;
    }
    
    // Render image previews
    function renderImagePreviews() {
        const previewContainer = document.getElementById('imagePreview');
        previewContainer.innerHTML = '';
        
        selectedImages.forEach((imageData, index) => {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'image-preview';
            previewDiv.innerHTML = `
                <img src="${imageData}" alt="Preview ${index + 1}">
                <button type="button" class="remove-image" data-index="${index}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            previewContainer.appendChild(previewDiv);
        });
    }
    
    // Reset form
    function resetForm() {
        document.getElementById('eventForm').reset();
        quill.root.innerHTML = '';
        document.getElementById('imagePreview').innerHTML = '';
        document.getElementById('eventModalLabel').textContent = 'Create New Event';
        eventToEdit = null;
    }
    
    // Show notification
    function showNotification(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 1060; min-width: 300px;';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
    
    // Simulate notification
    function simulateNotification(event) {
        console.log(`Notification sent for event: ${event.title}`);
        // In a real application, this would send emails or push notifications
    }
    
    // When modal is hidden, reset form
    document.getElementById('eventModal').addEventListener('hidden.bs.modal', function() {
        resetForm();
    });
});

document.getElementById("exportEvents").addEventListener("click", exportEvents);
document.getElementById("printCalendar").addEventListener("click", printCalendar);
document.getElementById("refreshCalendar").addEventListener("click", refreshCalendar);
document.getElementById("showCalendarHelp").addEventListener("click", showCalendarHelp);

// Date restriction helpers
function getMinDate() {
    const today = new Date();
    today.setHours(0,0,0,0);
    return today;
}

function getMaxDate() {
    const max = new Date();
    max.setMonth(max.getMonth() + 5);
    max.setHours(23,59,59,999);
    return max;
}

function isDateAllowed(dateStr) {
    if(!dateStr) return false;
    const selected = new Date(dateStr);
    selected.setHours(0,0,0,0);
    const min = getMinDate();
    const max = getMaxDate();
    return selected >= min && selected <= max;
}

function validateAndSetDateInput() {
    const dateInput = document.getElementById('eventDate');
    if(!dateInput) return;
    
    const today = new Date().toISOString().split('T')[0];
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 5);
    const maxDateStr = maxDate.toISOString().split('T')[0];
    
    dateInput.setAttribute('min', today);
    dateInput.setAttribute('max', maxDateStr);
    
    const warningDiv = document.getElementById('dateWarning');
    if(warningDiv) {
        dateInput.addEventListener('change', function() {
            if(this.value && !isDateAllowed(this.value)) {
                warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Invalid date! Select a date within next 5 months.';
                this.classList.add('is-invalid');
            } else {
                warningDiv.innerHTML = '';
                this.classList.remove('is-invalid');
            }
        });
    }
}


const defaultUI = document.getElementById("defaultEventManagement");
const pendingContainer = document.getElementById("pendingEventsContainer");

function loadPendingEvents() {
    const q = query(
        collection(db, "events"),
        where("approved", "==", false)
    );

    //const snapshot = await getDocs(q);
    onSnapshot(q, (snapshot) => {

        // 🔹 No pending events → show original UI
        if (snapshot.empty) {
            pendingContainer.classList.add("d-none");
            return;
        }

        // 🔹 Pending events exist
        pendingContainer.classList.remove("d-none");

        pendingContainer.innerHTML = `
            <div class="col-lg-4">
                <div class="calendar-container">
                    <h3 class="section-title">
                        <i class="fas fa-clock"></i> Pending Event Approvals
                    </h3>
                    <div class="list-group" id="pendingList"></div>
                </div>
            </div>
        `;

        const list = document.getElementById("pendingList");

        snapshot.forEach(docSnap => {
            const event = docSnap.data();

            // Create container div
            const item = document.createElement('div');
            item.className = 'list-group-item mb-2';

            // Event title
            const title = document.createElement('h6');
            title.className = 'mb-1';
            title.textContent = event.title ?? "Untitled Event";

            // Event description
            const desc = document.createElement('p');
            desc.className = 'mb-2 text-muted';
            desc.textContent = event.description ?? "";

            // Buttons container
            const btnContainer = document.createElement('div');
            btnContainer.className = 'd-flex gap-2';

            // Accept button
            const acceptBtn = document.createElement('button');
            acceptBtn.className = 'btn btn-success btn-sm';
            acceptBtn.textContent = 'Accept';
            acceptBtn.addEventListener('click', () => approveEvent(docSnap.id));

            // Reject button
            const rejectBtn = document.createElement('button');
            rejectBtn.className = 'btn btn-danger btn-sm';
            rejectBtn.textContent = 'Reject';
            rejectBtn.addEventListener('click', () => rejectEvent(docSnap.id));

            // Append buttons to container
            btnContainer.appendChild(acceptBtn);
            btnContainer.appendChild(rejectBtn);

            // Append all to item
            item.appendChild(title);
            item.appendChild(desc);
            item.appendChild(btnContainer);

            // Append item to list
            list.appendChild(item);
        });
    });
}

window.approveEvent = async (id) => {
    await updateDoc(doc(db, "events", id), {
        approved: true
    });
};

window.rejectEvent = async (id) => {
    await deleteDoc(doc(db, "events", id));
};
loadPendingEvents();