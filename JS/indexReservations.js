document.querySelectorAll('.btn-modify').forEach((button) => {
    button.addEventListener('click', function() {
        const reservationItem = this.closest('.reservation-item');
        const reservationId = reservationItem.dataset.id;
        
        document.getElementById('reservationId').value = reservationId;
        
        document.getElementById('reservationModal').style.display = 'block';
    });
});

function closeModal() {
    document.getElementById('reservationModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target === document.getElementById('reservationModal')) {
        closeModal();
    }
};