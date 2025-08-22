document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.sidebar a');
    const sections = document.querySelectorAll('.content section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetSection = this.getAttribute('data-section');
            
            navLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            
            sections.forEach(section => {
                section.style.display = section.id === targetSection ? 'block' : 'none';
            });
        });
    });



    // Modal functionality
    const addFlightBtn = document.getElementById('addFlightBtn');
    const addUserBtn = document.getElementById('addUserBtn');
    const addFlightModal = document.getElementById('addFlightModal');
    const addUserModal = document.getElementById('addUserModal');
    const closeBtns = document.getElementsByClassName('close');

    // Open modals
    addFlightBtn.onclick = () => addFlightModal.style.display = 'block';
    addUserBtn.onclick = () => addUserModal.style.display = 'block';

    // Close modals
    Array.from(closeBtns).forEach(btn => {
        btn.onclick = function() {
            addFlightModal.style.display = 'none';
            addUserModal.style.display = 'none';
        }
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == addFlightModal) {
            addFlightModal.style.display = 'none';
        }
        if (event.target == addUserModal) {
            addUserModal.style.display = 'none';
        }
    }

    // Handle form submissions
    document.getElementById('addFlightForm').onsubmit = function(e) {
        e.preventDefault();
        const newFlight = {
            number: document.getElementById('flightNumber').value,
            origin: document.getElementById('origin').value,
            destination: document.getElementById('destination').value,
            date: document.getElementById('date').value,
            time: document.getElementById('time').value
        };
        addFlightToTable(newFlight);
        addFlightModal.style.display = 'none';
        this.reset();
    };

    document.getElementById('addUserForm').onsubmit = function(e) {
        e.preventDefault();
        const newUser = {
            id: 'U' + (Math.floor(Math.random() * 1000) + 1).toString().padStart(3, '0'),
            name: document.getElementById('userName').value,
            email: document.getElementById('userEmail').value,
            role: document.getElementById('userRole').value
        };
        addUserToTable(newUser);
        addUserModal.style.display = 'none';
        this.reset();
    };

    function addFlightToTable(flight) {
        const row = flightTable.insertRow();
        Object.values(flight).forEach(text => {
            const cell = row.insertCell();
            cell.textContent = text;
        });
        const actionsCell = row.insertCell();
        actionsCell.innerHTML = '<button class="btn">Edit</button> <button class="btn">Delete</button>';
    }

    function addUserToTable(user) {
        const row = userTable.insertRow();
        Object.values(user).forEach(text => {
            const cell = row.insertCell();
            cell.textContent = text;
        });
        const actionsCell = row.insertCell();
        actionsCell.innerHTML = '<button class="btn">Edit</button> <button class="btn">Delete</button>';
    }
});

