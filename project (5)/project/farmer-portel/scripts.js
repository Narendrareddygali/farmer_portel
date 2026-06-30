document.getElementById('farmerForm').addEventListener('submit', function(event) {
    // Basic client-side validation
    const aadhaar = document.getElementById('aadhaar').value;
    const income = document.getElementById('income').value;
    
    if (!/^\d{12}$/.test(aadhaar)) {
        alert('Please enter a valid 12-digit Aadhaar number');
        event.preventDefault();
        return;
    }
    
    if (parseFloat(income) <= 0) {
        alert('Please enter a valid income amount');
        event.preventDefault();
        return;
    }
    
    // Additional validations can be added here
});