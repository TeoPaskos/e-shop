// Εμφάνιση ειδοποίησης όταν προστεθεί προϊόν στο καλάθι
function showCartNotification(productName) {
    const notif = document.createElement('div');
    notif.className = 'cart-notification';
    notif.innerText = `Το προϊόν "${productName}" προστέθηκε στο καλάθι!`;
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.classList.add('hide');
        setTimeout(() => notif.remove(), 500);
    }, 2000);
}

// Εύρεση όλων των φορμών προσθήκης στο καλάθι και προσθήκη event
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[action="add_to_card.php"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const name = form.closest('.product-card').querySelector('.product-name').innerText;
            showCartNotification(name);
        });
    });
});

// Αφαιρέθηκε ο κώδικας για το φίλτρο προϊόντων με JS