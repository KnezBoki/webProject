function updateReservationFilters() {
    const filterDate = document.getElementById('filterDate').value.trim();

    const requestBody = (filterDate === '') ? null : JSON.stringify({ filterDate }); //if date is null it shows all

    fetch('reservation_filter.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: requestBody
    })
        .then(response => response.json())
        .then(data => {
            const reservationList = document.getElementById('reservationList');
            reservationList.innerHTML = data.reservationList;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

//Event listener for the show all button
const filterAllButton = document.getElementById('filterAll');
filterAllButton.addEventListener('click', () => {
    document.getElementById('filterDate').value = '';

    updateReservationFilters();
});