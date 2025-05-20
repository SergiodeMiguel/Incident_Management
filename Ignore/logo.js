// Example JavaScript to enhance the UI

document.addEventListener('DOMContentLoaded', function() {
  // Set welcome message with username (example)
  const username = localStorage.getItem('username') || 'User';
  document.getElementById('welcome-message').textContent = `Hello, ${username}.`;
  
  // Example incident data (replace with your actual data fetching logic)
  const incidents = [
    { id: 'INC-001', title: 'Network Outage', status: 'open', category: 'IT', department: 'Technology', date: '2025-05-18' },
    { id: 'INC-002', title: 'Printer Malfunction', status: 'closed', category: 'Hardware', department: 'Admin', date: '2025-05-15' },
    { id: 'INC-003', title: 'Software License Issue', status: 'pending', category: 'Software', department: 'Finance', date: '2025-05-19' }
  ];
  
  // Update counts
  document.getElementById('open-count').textContent = incidents.filter(inc => inc.status === 'open').length;
  document.getElementById('closed-count').textContent = incidents.filter(inc => inc.status === 'closed').length;
  
  // Populate incident table
  const tableBody = document.getElementById('incident-list');
  
  incidents.forEach(incident => {
    const row = document.createElement('tr');
    
    // Create status badge class based on status
    const statusClass = `status-badge status-${incident.status}`;
    
    row.innerHTML = `
      <td class="admin-only">${incident.id}</td>
      <td>${incident.title}</td>
      <td><span class="${statusClass}">${incident.status.charAt(0).toUpperCase() + incident.status.slice(1)}</span></td>
      <td>${incident.category}</td>
      <td>${incident.department}</td>
      <td>${formatDate(incident.date)}</td>
      <td class="table-actions">
        <button class="action-btn edit-btn" onclick="editIncident('${incident.id}')">
          <i class="fas fa-edit"></i> Edit
        </button>
        <button class="action-btn delete-btn" onclick="deleteIncident('${incident.id}')">
          <i class="fas fa-trash"></i> Delete
        </button>
      </td>
    `;
    
    tableBody.appendChild(row);
  });
  
  // Check if user is admin and show admin-only elements if needed
  const isAdmin = localStorage.getItem('isAdmin') === 'true';
  if (isAdmin) {
    document.querySelectorAll('.admin-only').forEach(el => {
      el.style.display = 'table-cell';
    });
  }
});

// Helper function to format dates
function formatDate(dateString) {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

// Placeholder functions for actions
function editIncident(id) {
  window.location.href = `Edit_Incident.html?id=${id}`;
}

function deleteIncident(id) {
  if (confirm(`Are you sure you want to delete incident ${id}?`)) {
    // Delete logic here
    console.log(`Deleting incident ${id}`);
    // After successful deletion, refresh the page or update the table
  }
}

function logout() {
  // Clear user data
  localStorage.removeItem('username');
  localStorage.removeItem('isAdmin');
  // Redirect to login page
  window.location.href = 'Login.html';
}